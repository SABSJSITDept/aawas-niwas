<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomCategory;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
   public function index(Request $request)
{
    $date = $request->input('date', now()->toDateString());
    $today = Carbon::parse($date);
    $start = $today->copy();
    $end = $today->copy()->addDays(6); // next 7 days inclusive

    // ---------- 1) Today's check-ins / check-outs (sum of total_persons) ----------
    $checkInCount = FamilyBooking::whereDate('check_in_date', $today->toDateString())->sum('total_persons')
                  + GroupBooking::whereDate('check_in_date', $today->toDateString())->sum('total_persons');

    $checkOutCount = FamilyBooking::whereDate('check_out_date', $today->toDateString())->sum('total_persons')
                   + GroupBooking::whereDate('check_out_date', $today->toDateString())->sum('total_persons');

    // ---------- helper: apply "not allotted" conditions depending on existing column names ----------
    $applyNotAllotted = function ($query, $tableName) {
        // list of common allotment-related columns (ordered by likelihood)
        $candidates = [
            'room_allotted', 'is_allotted', 'allotted', 'allocated', 
            'room_id', 'allocated_room_id', 'allocated_room', 'assigned_room_id', 'assigned_room'
        ];

        foreach ($candidates as $col) {
            if (Schema::hasColumn($tableName, $col)) {
                // decide how to check based on column type / name:
                if (in_array($col, ['room_id','allocated_room_id','assigned_room_id','allocated_room','assigned_room'])) {
                    // treat NULL or 0 as not allotted
                    $query->where(function($q) use ($col) {
                        $q->whereNull($col)->orWhere($col, 0);
                    });
                } else {
                    // boolean-like columns: NULL or 0 => not allotted
                    $query->where(function($q) use ($col) {
                        $q->whereNull($col)->orWhere($col, 0);
                    });
                }
                // once matched, stop checking further columns
                return;
            }
        }

        // fallback: if none of the candidate columns exist -> do nothing (treat all pending as not-allotted)
        // If you prefer to exclude all in this case, uncomment next line:
        // $query->whereRaw('1 = 0');
    };

    // ---------- 2) Get grouped sums for next 7 days (family + group) ----------
    $familyGrouped = FamilyBooking::selectRaw("DATE(check_in_date) as date, SUM(total_persons) as persons")
        ->whereDate('check_in_date', '>=', $start->toDateString())
        ->whereDate('check_in_date', '<=', $end->toDateString())
        ->where('status', 'pending')
        ->when(true, function($q) use ($applyNotAllotted) {
            $applyNotAllotted($q, (new FamilyBooking)->getTable());
        })
        ->groupByRaw("DATE(check_in_date)")
        ->get()
        ->keyBy('date')
        ->map(fn($r) => (int)$r->persons)
        ->toArray();

    $groupGrouped = GroupBooking::selectRaw("DATE(check_in_date) as date, SUM(total_persons) as persons")
        ->whereDate('check_in_date', '>=', $start->toDateString())
        ->whereDate('check_in_date', '<=', $end->toDateString())
        ->where('status', 'pending')
        ->when(true, function($q) use ($applyNotAllotted) {
            $applyNotAllotted($q, (new GroupBooking)->getTable());
        })
        ->groupByRaw("DATE(check_in_date)")
        ->get()
        ->keyBy('date')
        ->map(fn($r) => (int)$r->persons)
        ->toArray();

    // merge date-wise
    $merged = [];
    for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
        $ds = $d->toDateString();
        $merged[$ds] = ($familyGrouped[$ds] ?? 0) + ($groupGrouped[$ds] ?? 0);
    }

    $notAllottedNext7 = collect($merged)->map(fn($persons, $date) => [
        'date' => $date,
        'persons' => (int)$persons
    ])->values()->toArray();

    // ---------- 3) overall notAllottedCount (sum total_persons where pending & not allotted & not already checked-out) ----------
    $familyPending = FamilyBooking::where('status','pending')
        ->when(true, function($q) use ($applyNotAllotted) {
            $applyNotAllotted($q, (new FamilyBooking)->getTable());
        })
        ->where(function($q) use ($today) {
            $q->whereNull('check_out_date')->orWhereDate('check_out_date', '>=', $today->toDateString());
        })
        ->sum('total_persons');

    $groupPending = GroupBooking::where('status','pending')
        ->when(true, function($q) use ($applyNotAllotted) {
            $applyNotAllotted($q, (new GroupBooking)->getTable());
        })
        ->where(function($q) use ($today) {
            $q->whereNull('check_out_date')->orWhereDate('check_out_date', '>=', $today->toDateString());
        })
        ->sum('total_persons');

    $notAllottedCount = $familyPending + $groupPending;

    // ---------- 4) pass to view (add other dashboard vars as needed) ----------
    $totalRooms = $totalRooms ?? 0;
    $bookedRooms = $bookedRooms ?? 0;
    $emptyRooms = $emptyRooms ?? 0;
    $totalCapacity = $totalCapacity ?? 0;
    $bookedCapacity = $bookedCapacity ?? 0;
    $availableCapacity = $availableCapacity ?? 0;

    return view('admin.dashboard', compact(
        'date',
        'checkInCount',
        'checkOutCount',
        'notAllottedNext7',
        'notAllottedCount',
        'totalRooms','bookedRooms','emptyRooms',
        'totalCapacity','bookedCapacity','availableCapacity'
    ));
}
}
