<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $today = $request->input('date') ?? now()->toDateString();
        $yesterday = \Carbon\Carbon::parse($today)->subDay()->toDateString();

        // -----------------------------
        // SLOT 1: Before 10 AM
        $familySlot0 = DB::table('family_booking')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '<', '10:00:00')
            ->sum('total_persons');

        $groupSlot0 = DB::table('group_bookings')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '<', '10:00:00')
            ->sum('total_persons');

        // SLOT 2: 10 AM – 4 PM
        $familySlot1 = DB::table('family_booking')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>=', '10:00:00')
            ->whereTime('check_in_time', '<=', '16:00:00')
            ->sum('total_persons');

        $groupSlot1 = DB::table('group_bookings')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>=', '10:00:00')
            ->whereTime('check_in_time', '<=', '16:00:00')
            ->sum('total_persons');

        // SLOT 3: 4 PM – 10 PM
        $familySlot2 = DB::table('family_booking')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>', '16:00:00')
            ->whereTime('check_in_time', '<=', '22:00:00')
            ->sum('total_persons');

        $groupSlot2 = DB::table('group_bookings')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>', '16:00:00')
            ->whereTime('check_in_time', '<=', '22:00:00')
            ->sum('total_persons');

        // SLOT 4: 10 PM – 12 AM
        $familySlot3 = DB::table('family_booking')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>', '22:00:00')
            ->sum('total_persons');

        $groupSlot3 = DB::table('group_bookings')
            ->whereDate('check_in_date', $today)
            ->whereTime('check_in_time', '>', '22:00:00')
            ->sum('total_persons');

        // -----------------------------
        // YESTERDAY KE रुके हुए लोग (not checked out yet)
        $familyYesterday = DB::table('family_booking')
            ->whereDate('check_in_date', '<', $today)
            ->whereDate('check_out_date', '>=', $today)
            ->sum('total_persons');

        $groupYesterday = DB::table('group_bookings')
            ->whereDate('check_in_date', '<', $today)
            ->whereDate('check_out_date', '>=', $today)
            ->sum('total_persons');

        $yesterdayTotal = $familyYesterday + $groupYesterday;

        // -----------------------------
        // SLOT TOTALS
        $slot0Total = $familySlot0 + $groupSlot0;
        $slot1Total = $familySlot1 + $groupSlot1;
        $slot2Total = $familySlot2 + $groupSlot2;
        $slot3Total = $familySlot3 + $groupSlot3;

        // आज का total (check-in only)
        $todayTotal = $slot0Total + $slot1Total + $slot2Total + $slot3Total;

        // FINAL TOTAL = रुके हुए + आज के
        $grandTotal = $yesterdayTotal + $todayTotal;

        return view('admin.daily-report', compact(
            'today',
            'familySlot0', 'groupSlot0', 'slot0Total',
            'familySlot1', 'groupSlot1', 'slot1Total',
            'familySlot2', 'groupSlot2', 'slot2Total',
            'familySlot3', 'groupSlot3', 'slot3Total',
            'yesterdayTotal',
            'todayTotal',
            'grandTotal'
        ));
    }


public function downloadPDF(Request $request)
{
    $today = $request->input('date') ?? now()->toDateString();
    $yesterday = \Carbon\Carbon::parse($today)->subDay()->toDateString();

    // SLOT 1: 4 AM – 10 AM
    $familySlot0 = DB::table('family_booking')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>=', '04:00:00')
        ->whereTime('check_in_time', '<', '10:00:00')
        ->sum('total_persons');

    $groupSlot0 = DB::table('group_bookings')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>=', '04:00:00')
        ->whereTime('check_in_time', '<', '10:00:00')
        ->sum('total_persons');

    // SLOT 2: 10 AM – 4 PM
    $familySlot1 = DB::table('family_booking')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>=', '10:00:00')
        ->whereTime('check_in_time', '<=', '16:00:00')
        ->sum('total_persons');

    $groupSlot1 = DB::table('group_bookings')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>=', '10:00:00')
        ->whereTime('check_in_time', '<=', '16:00:00')
        ->sum('total_persons');

    // SLOT 3: 4 PM – 10 PM
    $familySlot2 = DB::table('family_booking')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>', '16:00:00')
        ->whereTime('check_in_time', '<=', '22:00:00')
        ->sum('total_persons');

    $groupSlot2 = DB::table('group_bookings')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>', '16:00:00')
        ->whereTime('check_in_time', '<=', '22:00:00')
        ->sum('total_persons');

    // SLOT 4: 10 PM – 12 AM
    $familySlot3 = DB::table('family_booking')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>', '22:00:00')
        ->sum('total_persons');

    $groupSlot3 = DB::table('group_bookings')
        ->whereDate('check_in_date', $today)
        ->whereTime('check_in_time', '>', '22:00:00')
        ->sum('total_persons');

    // YESTERDAY से रुके हुए लोग
    $familyYesterday = DB::table('family_booking')
        ->whereDate('check_in_date', '<', $today)
        ->whereDate('check_out_date', '>=', $today)
        ->sum('total_persons');

    $groupYesterday = DB::table('group_bookings')
        ->whereDate('check_in_date', '<', $today)
        ->whereDate('check_out_date', '>=', $today)
        ->sum('total_persons');

    $yesterdayTotal = $familyYesterday + $groupYesterday;

    // SLOT TOTALS
    $slot0Total = $familySlot0 + $groupSlot0;
    $slot1Total = $familySlot1 + $groupSlot1;
    $slot2Total = $familySlot2 + $groupSlot2;
    $slot3Total = $familySlot3 + $groupSlot3;

    $todayTotal = $slot0Total + $slot1Total + $slot2Total + $slot3Total;
    $grandTotal = $todayTotal + $yesterdayTotal;

    $pdf = PDF::loadView('admin.daily-report-pdf', compact(
        'today',
        'familySlot0', 'groupSlot0', 'slot0Total',
        'familySlot1', 'groupSlot1', 'slot1Total',
        'familySlot2', 'groupSlot2', 'slot2Total',
        'familySlot3', 'groupSlot3', 'slot3Total',
        'yesterdayTotal',
        'todayTotal',
        'grandTotal'
    ));

    return $pdf->download('daily-report-' . $today . '.pdf');
}


}
