<?php

namespace App\Http\Controllers\Api\DashboardReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RoomCategory;
use App\Models\RoomFeatures;

class RoomReportController extends Controller
{
    /**
     * Return overall room report, accounting for booked rooms from `booked_rooms` table.
     *
     * Logic:
     * - inventory_capacity = SUM( room_count * COALESCE(total_capacity,0) )  -- as before
     * - booked_total_all = SUM(booked_rooms.total_capacity)                  -- all rows in booked_rooms
     * - booked_current = SUM(booked_rooms.total_capacity) WHERE today BETWEEN check_in_date AND check_out_date
     * - available_total = max(0, inventory_capacity - booked_total_all)
     * - available_current = max(0, inventory_capacity - booked_current)
     *
     * Also calculates per-hotel breakdown with same logic.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // room count expression (MySQL-compatible)
        $roomCountExpr = "(
            CASE
                WHEN room_number IS NULL OR TRIM(room_number) = '' THEN 0
                ELSE (CHAR_LENGTH(room_number) - CHAR_LENGTH(REPLACE(room_number, ',', ''))) + 1
            END
        )";

        // 1) Inventory totals computed from room_category (room_count * total_capacity)
        $total_rows = RoomCategory::count();

        $total_room_numbers = (int) RoomCategory::selectRaw("
            COALESCE(SUM({$roomCountExpr}), 0) as total_rooms
        ")->value('total_rooms');

        // Align logic with RoomsExportAll: Include only active rooms
        $inventory_total_capacity = 0;
        $inventory_total_extra_capacity = 0;

        $roomCategories = RoomCategory::whereHas('hotel', function ($query) {
            $query->where('status', 'active');
        })->get();

        foreach ($roomCategories as $category) {
            $roomNumbers = explode(',', $category->room_number);

            foreach ($roomNumbers as $room) {
                $room = trim($room);

                // Check if room is active in room_features
                $isActive = RoomFeatures::where('hotel_id', $category->hotel_id)
                    ->where('room_number', $room)
                    ->where('status', 'active')
                    ->exists();

                if (!$isActive) {
                    continue; // Skip inactive rooms
                }

                $inventory_total_capacity += $category->total_capacity;
                $inventory_total_extra_capacity += $category->extra_capacity;
            }
        }

        // 2) Booked capacities from booked_rooms table
        // booked_total_all: sum of total_capacity from all booked_rooms rows (guard COALESCE)
        $booked_total_all = (float) DB::table('booked_rooms')
            ->selectRaw("COALESCE(SUM(COALESCE(total_capacity,0)),0) as s")
            ->value('s');

        // booked_current: sum of total_capacity for bookings active today (check_in_date <= today <= check_out_date)
        // handle NULL check_out_date as open-ended (treat as active if check_in_date <= today)
        $booked_current = (float) DB::table('booked_rooms')
            ->where(function($q) use ($today) {
                $q->where(function($q2) use ($today) {
                    $q2->whereDate('check_in_date', '<=', $today)
                       ->whereDate('check_out_date', '>=', $today);
                })
                ->orWhere(function($q3) use ($today) {
                    $q3->whereDate('check_in_date', '<=', $today)
                       ->whereNull('check_out_date');
                });
            })
            ->selectRaw("COALESCE(SUM(COALESCE(total_capacity,0)),0) as s")
            ->value('s');

        // 3) Available capacities (clamped to zero)
        $available_after_all_bookings = max(0, $inventory_total_capacity - $booked_total_all);
        $available_after_current_bookings = max(0, $inventory_total_capacity - $booked_current);

        // 4) Hotel-wise breakdown: inventory, booked (all & current), available
        // We'll compute per-hotel inventory the same way as before, and join aggregated booked_rooms per hotel.
        $byHotelInventory = RoomCategory::selectRaw("
                hotel_id,
                COUNT(*) as rows_count,
                COALESCE(SUM( ({$roomCountExpr}) * COALESCE(total_capacity,0) ), 0) as inventory_capacity,
                COALESCE(SUM( ({$roomCountExpr}) * COALESCE(extra_capacity,0) ), 0) as inventory_extra_capacity,
                COALESCE(SUM( {$roomCountExpr} ), 0) as room_numbers_count
            ")
            ->groupBy('hotel_id')
            ->get()
            ->keyBy('hotel_id');

        // Booked aggregates per hotel (all)
        $bookedPerHotelAll = DB::table('booked_rooms')
            ->selectRaw("hotel_id, COALESCE(SUM(COALESCE(total_capacity,0)),0) as booked_all")
            ->groupBy('hotel_id')
            ->pluck('booked_all', 'hotel_id'); // returns [hotel_id => booked_all]

        // Booked aggregates per hotel (current/active)
        $bookedPerHotelCurrent = DB::table('booked_rooms')
            ->where(function($q) use ($today) {
                $q->where(function($q2) use ($today) {
                    $q2->whereDate('check_in_date', '<=', $today)
                       ->whereDate('check_out_date', '>=', $today);
                })
                ->orWhere(function($q3) use ($today) {
                    $q3->whereDate('check_in_date', '<=', $today)
                       ->whereNull('check_out_date');
                });
            })
            ->selectRaw("hotel_id, COALESCE(SUM(COALESCE(total_capacity,0)),0) as booked_current")
            ->groupBy('hotel_id')
            ->pluck('booked_current', 'hotel_id');

        // Build by_hotel array merging inventory + booked
        $by_hotel = [];
        $hotelIds = collect(array_merge(
            $byHotelInventory->keys()->all(),
            $bookedPerHotelAll->keys()->all(),
            $bookedPerHotelCurrent->keys()->all()
        ))->unique();

        foreach ($hotelIds as $hid) {
            $inv = $byHotelInventory->has($hid) ? $byHotelInventory->get($hid) : null;
            $inv_capacity = $inv ? (float) $inv->inventory_capacity : 0;
            $inv_extra = $inv ? (float) $inv->inventory_extra_capacity : 0;
            $rows_count = $inv ? (int) $inv->rows_count : 0;
            $room_numbers_count = $inv ? (int) $inv->room_numbers_count : 0;

            $booked_all = isset($bookedPerHotelAll[$hid]) ? (float) $bookedPerHotelAll[$hid] : 0;
            $booked_current = isset($bookedPerHotelCurrent[$hid]) ? (float) $bookedPerHotelCurrent[$hid] : 0;

            $available_all = max(0, $inv_capacity - $booked_all);
            $available_current = max(0, $inv_capacity - $booked_current);

            $by_hotel[] = [
                'hotel_id' => $hid,
                'rows_count' => $rows_count,
                'room_numbers_count' => $room_numbers_count,
                'inventory_capacity' => $inv_capacity,
                'inventory_extra_capacity' => $inv_extra,
                'booked_total_all' => $booked_all,
                'booked_current' => $booked_current,
                'available_after_all_bookings' => $available_all,
                'available_after_current_bookings' => $available_current,
            ];
        }

        // Format response
        $response = [
            'total_rows' => $total_rows,
            'total_room_numbers' => $total_room_numbers,
            'inventory_total_capacity' => $inventory_total_capacity,
            'inventory_total_extra_capacity' => $inventory_total_extra_capacity,
            'booked_total_all' => $booked_total_all,
            'booked_current' => $booked_current,
            'available_after_all_bookings' => $available_after_all_bookings,
            'available_after_current_bookings' => $available_after_current_bookings,
            'by_hotel' => $by_hotel,
        ];

        return response()->json([
            'success' => true,
            'data' => $response
        ], 200);
    }
}
