<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ParivahanReportController extends Controller
{
    public function datewiseStayReport(Request $request)
    {
        $selectedDate = $request->input('date') ?? Carbon::now()->toDateString();
        $selectedHotel = $request->input('hotel_name');
        $selectedTime = $request->input('report_time') ?? Carbon::now()->format('H:i');
        $now = Carbon::parse($selectedDate . ' ' . $selectedTime);

        $hotels = DB::table('hotel_details')
            ->where('status', 'active')
            ->get();

        $report = [];

        foreach ($hotels as $hotel) {
            if ($selectedHotel && $hotel->hotel_name !== $selectedHotel) {
                continue;
            }
            // Get distinct booking_ids for this hotel on the selected date
            $bookingIds = DB::table('booked_rooms')
                ->where('hotel_id', $hotel->id)
                ->whereDate('check_in_date', '<=', $selectedDate)
                ->whereDate('check_out_date', '>=', $selectedDate)
                ->pluck('booking_id')
                ->unique();

            $totalStaying = 0;
            foreach ($bookingIds as $bookingId) {
                // VIP booking: booking_id like V-123
                if (strpos($bookingId, 'V-') === 0) {
                    $vipId = intval(substr($bookingId, 2));
                    $vip = DB::table('forms')->where('id', $vipId)->first();
                    if ($vip) {
                        // Check-in logic
                        $checkInDateTime = null;
                        if (!empty($vip->check_in_date)) {
                            $checkInDateTime = Carbon::parse($vip->check_in_date . ' ' . ($vip->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($vip->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($vip->check_out_date . ' ' . ($vip->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $vip->total_persons ?? 1;
                        }
                        continue;
                    }
                }
                // Family booking: booking_id like F-xxx
                elseif (strpos($bookingId, 'F-') === 0) {
                    $family = DB::table('family_bookings')->where('booking_id', $bookingId)->first();
                    if ($family) {
                        $checkInDateTime = null;
                        if (!empty($family->check_in_date)) {
                            $checkInDateTime = Carbon::parse($family->check_in_date . ' ' . ($family->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($family->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($family->check_out_date . ' ' . ($family->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $family->total_persons ?? 0;
                        }
                        continue;
                    }
                }
                // Group booking: booking_id like G-xxx
                elseif (strpos($bookingId, 'G-') === 0) {
                    $group = DB::table('group_bookings')->where('booking_id', $bookingId)->first();
                    if ($group) {
                        $checkInDateTime = null;
                        if (!empty($group->check_in_date)) {
                            $checkInDateTime = Carbon::parse($group->check_in_date . ' ' . ($group->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($group->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($group->check_out_date . ' ' . ($group->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $group->total_persons ?? 0;
                        }
                        continue;
                    }
                }
            }

            $report[] = [
                'hotel_name' => $hotel->hotel_name,
                'date' => $selectedDate,
                'total_staying' => $totalStaying
            ];
        }

        return view('admin.reports.parivahan', [
            'report' => $report,
            'selectedDate' => $selectedDate,
            'hotels' => $hotels,
        ]);
    }
    public function downloadPdf(Request $request)
    {
        $selectedDate = $request->input('date') ?? now()->toDateString();
        $now = Carbon::now();

        $hotels = DB::table('hotel_details')
            ->where('status', 'active')
            ->get();

        $report = [];

        foreach ($hotels as $hotel) {
            // Get distinct booking_ids for this hotel on the selected date
            $bookingIds = DB::table('booked_rooms')
                ->where('hotel_id', $hotel->id)
                ->whereDate('check_in_date', '<=', $selectedDate)
                ->whereDate('check_out_date', '>=', $selectedDate)
                ->pluck('booking_id')
                ->unique();

            $totalStaying = 0;
            foreach ($bookingIds as $bookingId) {
                // VIP booking: booking_id like V-123
                if (strpos($bookingId, 'V-') === 0) {
                    $vipId = intval(substr($bookingId, 2));
                    $vip = DB::table('forms')->where('id', $vipId)->first();
                    if ($vip) {
                        $checkInDateTime = null;
                        if (!empty($vip->check_in_date)) {
                            $checkInDateTime = Carbon::parse($vip->check_in_date . ' ' . ($vip->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($vip->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($vip->check_out_date . ' ' . ($vip->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $vip->total_persons ?? 1;
                        }
                        continue;
                    }
                }
                // Family booking: booking_id like F-xxx
                elseif (strpos($bookingId, 'F-') === 0) {
                    $family = DB::table('family_bookings')->where('booking_id', $bookingId)->first();
                    if ($family) {
                        $checkInDateTime = null;
                        if (!empty($family->check_in_date)) {
                            $checkInDateTime = Carbon::parse($family->check_in_date . ' ' . ($family->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($family->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($family->check_out_date . ' ' . ($family->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $family->total_persons ?? 0;
                        }
                        continue;
                    }
                }
                // Group booking: booking_id like G-xxx
                elseif (strpos($bookingId, 'G-') === 0) {
                    $group = DB::table('group_bookings')->where('booking_id', $bookingId)->first();
                    if ($group) {
                        $checkInDateTime = null;
                        if (!empty($group->check_in_date)) {
                            $checkInDateTime = Carbon::parse($group->check_in_date . ' ' . ($group->check_in_time ?? '00:00:00'));
                        }
                        $checkOutDateTime = null;
                        if (!empty($group->check_out_date)) {
                            $checkOutDateTime = Carbon::parse($group->check_out_date . ' ' . ($group->check_out_time ?? '23:59:59'));
                        }
                        if ($checkInDateTime && $checkInDateTime->lessThanOrEqualTo($now) && (!$checkOutDateTime || $checkOutDateTime->greaterThan($now))) {
                            $totalStaying += $group->total_persons ?? 0;
                        }
                        continue;
                    }
                }
            }

            $report[] = [
                'hotel_name' => $hotel->hotel_name,
                'date' => $selectedDate,
                'total_staying' => $totalStaying
            ];
        }

        $pdf = Pdf::loadView('admin.reports.parivahan_pdf', [
            'report' => $report,
            'selectedDate' => $selectedDate
        ]);

        return $pdf->download('Parivahan_Report_' . $selectedDate . '.pdf');
    }

}

