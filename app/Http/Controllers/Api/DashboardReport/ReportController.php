<?php

namespace App\Http\Controllers\Api\DashboardReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;

class ReportController extends Controller
{
    /**
     * Return all dashboard report values in one JSON
     * GET /api/dashboard-report/summary
     */
public function summary(Request $request)
{
    // make sure you have: use Carbon\Carbon; at top of the controller file
    $today = Carbon::today()->toDateString(); // YYYY-MM-DD
    $tomorrow = Carbon::tomorrow()->toDateString();
    $sevenDaysLater = Carbon::tomorrow()->addDays(6)->toDateString(); // next 7 days inclusive of tomorrow

    // ---------------------------
    // TODAY check-ins / check-outs
    // ---------------------------
    $familyTodayCheckIn = FamilyBooking::whereDate('check_in_date', '=', $today)->sum('total_persons');
    $groupTodayCheckIn  = GroupBooking::whereDate('check_in_date', '=', $today)->sum('total_persons');
    $today_check_in = $familyTodayCheckIn + $groupTodayCheckIn;

    $familyTodayCheckOut = FamilyBooking::whereDate('check_out_date', '=', $today)->sum('total_persons');
    $groupTodayCheckOut  = GroupBooking::whereDate('check_out_date', '=', $today)->sum('total_persons');
    $today_check_out = $familyTodayCheckOut + $groupTodayCheckOut;

    // ---------------------------
    // NEXT 7 days check-ins (tomorrow -> sevenDaysLater)
    // ---------------------------
    $familyNext7 = FamilyBooking::whereDate('check_in_date', '>=', $tomorrow)
                    ->whereDate('check_in_date', '<=', $sevenDaysLater)
                    ->sum('total_persons');

    $groupNext7 = GroupBooking::whereDate('check_in_date', '>=', $tomorrow)
                    ->whereDate('check_in_date', '<=', $sevenDaysLater)
                    ->sum('total_persons');

    $next_seven_days_check_in = $familyNext7 + $groupNext7;

    // Datewise for next 7 days
    $familyByDate = FamilyBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as total')
                    ->whereDate('check_in_date', '>=', $tomorrow)
                    ->whereDate('check_in_date', '<=', $sevenDaysLater)
                    ->groupBy('date')
                    ->pluck('total', 'date');

    $groupByDate = GroupBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as total')
                   ->whereDate('check_in_date', '>=', $tomorrow)
                   ->whereDate('check_in_date', '<=', $sevenDaysLater)
                   ->groupBy('date')
                   ->pluck('total', 'date');

    $datewise_next7 = [];
    $current = Carbon::createFromFormat('Y-m-d', $tomorrow);
    $end = Carbon::createFromFormat('Y-m-d', $sevenDaysLater);

    while ($current->lte($end)) {
        $dateStr = $current->toDateString();

        $f = (int) ($familyByDate->get($dateStr) ?? 0);
        $g = (int) ($groupByDate->get($dateStr) ?? 0);

        $datewise_next7[] = [
            'date' => $dateStr,
            'total_persons' => $f + $g,
            'breakdown' => [
                'family' => $f,
                'group' => $g,
            ],
        ];

        $current->addDay();
    }

    // ---------------------------
    // TILL TODAY NOT ALLOTTED (only today's check_in_date AND status = 'pending')
    // ---------------------------
    $familyPendingToday = FamilyBooking::whereDate('check_in_date', '=', $today)
                            ->where('status', 'pending')
                            ->sum('total_persons');

    $groupPendingToday = GroupBooking::whereDate('check_in_date', '=', $today)
                            ->where('status', 'pending')
                            ->sum('total_persons');

    $till_today_not_allotted = $familyPendingToday + $groupPendingToday;

    // ---------------------------
    // PENDING ACTIVE (check_in_date <= today, status = 'pending', AND (check_out_date >= today OR check_out_date IS NULL))
    // includes bookings that came today or before, still pending and not checked-out yet
    // ---------------------------

    $familyPendingActive = FamilyBooking::whereDate('check_in_date', '<=', $today)
                        ->where('status', 'pending')
                        ->where(function($q) use ($today) {
                            $q->whereDate('check_out_date', '>=', $today)
                              ->orWhereNull('check_out_date');
                        })
                        ->sum('total_persons');

    $groupPendingActive = GroupBooking::whereDate('check_in_date', '<=', $today)
                        ->where('status', 'pending')
                        ->where(function($q) use ($today) {
                            $q->whereDate('check_out_date', '>=', $today)
                              ->orWhereNull('check_out_date');
                        })
                        ->sum('total_persons');

    $pending_active_total = $familyPendingActive + $groupPendingActive;

    // Datewise pending active (grouped by check_in_date)
    $familyPendingByDate = FamilyBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as total')
                        ->whereDate('check_in_date', '<=', $today)
                        ->where('status', 'pending')
                        ->where(function($q) use ($today) {
                            $q->whereDate('check_out_date', '>=', $today)
                              ->orWhereNull('check_out_date');
                        })
                        ->groupBy('date')
                        ->pluck('total', 'date');

    $groupPendingByDate = GroupBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as total')
                        ->whereDate('check_in_date', '<=', $today)
                        ->where('status', 'pending')
                        ->where(function($q) use ($today) {
                            $q->whereDate('check_out_date', '>=', $today)
                              ->orWhereNull('check_out_date');
                        })
                        ->groupBy('date')
                        ->pluck('total', 'date');

    $pending_date_keys = $familyPendingByDate->keys()->merge($groupPendingByDate->keys())->unique()->sort();

    $pending_datewise = [];
    foreach ($pending_date_keys as $dateKey) {
        $f = (int) ($familyPendingByDate->get($dateKey) ?? 0);
        $g = (int) ($groupPendingByDate->get($dateKey) ?? 0);
        $pending_datewise[] = [
            'date' => $dateKey,
            'total_persons' => $f + $g,
            'breakdown' => [
                'family' => $f,
                'group' => $g,
            ],
        ];
    }

    // ---------------------------
    // Final response
    // ---------------------------
    return response()->json([
        'success' => true,
        'data' => [
            'today_check_in' => (int) $today_check_in,
            'today_check_out' => (int) $today_check_out,
            'next_seven_days_check_in' => (int) $next_seven_days_check_in,
            'till_today_not_allotted' => (int) $till_today_not_allotted,

            'breakdown' => [
                'family' => [
                    'today_check_in' => (int) $familyTodayCheckIn,
                    'today_check_out' => (int) $familyTodayCheckOut,
                    'next_seven_days_check_in' => (int) $familyNext7,
                    'till_today_not_allotted' => (int) $familyPendingToday,
                ],
                'group' => [
                    'today_check_in' => (int) $groupTodayCheckIn,
                    'today_check_out' => (int) $groupTodayCheckOut,
                    'next_seven_days_check_in' => (int) $groupNext7,
                    'till_today_not_allotted' => (int) $groupPendingToday,
                ],
            ],

            'next_seven_days_datewise' => $datewise_next7,

            // Pending active (today and past checkins still pending & not checked-out)
            'pending_active_total' => (int) $pending_active_total,
            'pending_active_breakdown' => [
                'family' => (int) $familyPendingActive,
                'group' => (int) $groupPendingActive,
            ],
            'pending_active_datewise' => $pending_datewise,
        ],
    ], 200);
}



    /**
     * Individual endpoints (optional) - examples
     * You can call these if you prefer separate endpoints.
     */

    public function todayCheckIn()
    {
        $today = Carbon::today()->toDateString();
        $total = FamilyBooking::whereDate('check_in_date', $today)->sum('total_persons')
               + GroupBooking::whereDate('check_in_date', $today)->sum('total_persons');

        return response()->json(['success' => true, 'today_check_in' => (int) $total]);
    }

    public function todayCheckOut()
    {
        $today = Carbon::today()->toDateString();
        $total = FamilyBooking::whereDate('check_out_date', $today)->sum('total_persons')
               + GroupBooking::whereDate('check_out_date', $today)->sum('total_persons');

        return response()->json(['success' => true, 'today_check_out' => (int) $total]);
    }

    public function nextSevenDaysCheckIn(): JsonResponse
{
    $start = Carbon::tomorrow()->toDateString();               // e.g. 2025-10-21
    $end = Carbon::tomorrow()->addDays(6)->toDateString();     // 7 days total (tomorrow + 6 days)

    // total across both tables (same as your original)
    $total = FamilyBooking::whereDate('check_in_date', '>=', $start)
             ->whereDate('check_in_date', '<=', $end)
             ->sum('total_persons')
           + GroupBooking::whereDate('check_in_date', '>=', $start)
             ->whereDate('check_in_date', '<=', $end)
             ->sum('total_persons');

    // Get sums grouped by date from each model
    $familyByDate = FamilyBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as persons')
                    ->whereDate('check_in_date', '>=', $start)
                    ->whereDate('check_in_date', '<=', $end)
                    ->groupBy('date')
                    ->pluck('persons', 'date'); // returns [ '2025-10-21' => 5, ... ]

    $groupByDate = GroupBooking::selectRaw('DATE(check_in_date) as date, SUM(total_persons) as persons')
                   ->whereDate('check_in_date', '>=', $start)
                   ->whereDate('check_in_date', '<=', $end)
                   ->groupBy('date')
                   ->pluck('persons', 'date');

    // Merge the two plucks (both are Collections keyed by date)
    $merged = $familyByDate->mergeRecursive($groupByDate)->map(function ($value) {
        // mergeRecursive may give array if keys duplicated; normalize to int sum
        if (is_array($value)) {
            return array_sum($value);
        }
        return (int) $value;
    });

    // Build the datewise array for each day in range (fill 0 if missing)
    $dateWise = [];
    $current = Carbon::createFromFormat('Y-m-d', $start);
    $endCarbon = Carbon::createFromFormat('Y-m-d', $end);

    while ($current->lte($endCarbon)) {
        $dateStr = $current->toDateString();
        $count = isset($merged[$dateStr]) ? (int) $merged[$dateStr] : 0;

        $dateWise[] = [
            'date' => $dateStr,
            'total_persons' => $count,
        ];

        $current->addDay();
    }

    return response()->json([
        'success' => true,
        'next_seven_days_check_in' => (int) $total,
        'datewise' => $dateWise,
    ]);
}

    public function tillTodayNotAllotted()
    {
        $today = Carbon::today()->toDateString();

        $total = FamilyBooking::whereDate('check_in_date', $today)
                 ->where('status', 'pending')->sum('total_persons')
               + GroupBooking::whereDate('check_in_date', $today)
                 ->where('status', 'pending')->sum('total_persons');

        return response()->json(['success' => true, 'till_today_not_allotted' => (int) $total]);
    }
}
