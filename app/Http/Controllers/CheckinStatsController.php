<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckinStatsController extends Controller
{
public function index(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $today = Carbon::today();
        $futureDates = collect();
        for ($i = 0; $i < 5; $i++) {
            $futureDates->push($today->copy()->addDays($i)->toDateString());
        }

        // Earliest check-in from 3 tables
        $startDate = $from ?? min(array_filter([
            DB::table('forms')->min('check_in_date'),
            DB::table('family_booking')->min('check_in_date'),
            DB::table('group_bookings')->min('check_in_date'),
        ]));

        $endDate = $to ?? Carbon::today()->addDays(5)->toDateString();

        // All dates with check-in OR check-out activity
        $activeDates = DB::table('forms')->selectRaw('DATE(check_in_date) as date')
                ->whereNotNull('check_in_date')
            ->union(DB::table('forms')->selectRaw('DATE(check_out_date) as date')
                ->whereNotNull('check_out_date'))
            ->union(DB::table('family_booking')->selectRaw('DATE(check_in_date) as date')
                ->whereNotNull('check_in_date'))
            ->union(DB::table('family_booking')->selectRaw('DATE(check_out_date) as date')
                ->whereNotNull('check_out_date'))
            ->union(DB::table('group_bookings')->selectRaw('DATE(check_in_date) as date')
                ->whereNotNull('check_in_date'))
            ->union(DB::table('group_bookings')->selectRaw('DATE(check_out_date) as date')
                ->whereNotNull('check_out_date'))
            ->pluck('date')
            ->merge($futureDates)
            ->unique()
            ->sort()
            ->filter(fn($d) => $d >= $startDate && $d <= $endDate)
            ->values();

        $report = collect();

        foreach ($activeDates as $date) {
            // Check-ins on this date
            $checkin = DB::table('forms')->whereDate('check_in_date', $date)->count()
                    + DB::table('family_booking')->whereDate('check_in_date', $date)->sum('total_persons')
                    + DB::table('group_bookings')->whereDate('check_in_date', $date)->sum('total_persons');

            // Check-outs on this date
            $checkout = DB::table('forms')->whereDate('check_out_date', $date)->count()
                    + DB::table('family_booking')->whereDate('check_out_date', $date)->sum('total_persons')
                    + DB::table('group_bookings')->whereDate('check_out_date', $date)->sum('total_persons');

            // Staying on this date
            $staying = DB::table('forms')->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->count()
                    + DB::table('family_booking')->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->sum('total_persons')
                    + DB::table('group_bookings')->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->sum('total_persons');

            $report->push([
                'date' => $date,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'staying' => $staying,
            ]);
        }

        // Paginate manually
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paged = new LengthAwarePaginator(
            $report->forPage($currentPage, $perPage),
            $report->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.reports.datewise-checkin-report', [
            'report' => $paged,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        // Export full report (not paginated)
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $startDate = $from ?? min(array_filter([
            DB::table('forms')->min('check_in_date'),
            DB::table('family_booking')->min('check_in_date'),
            DB::table('group_bookings')->min('check_in_date'),
        ]));

        $endDate = $to ?? Carbon::today()->addDays(10)->toDateString();

        // Same checkin & checkout logic
        $checkins = DB::table(function ($query) {
            $query->selectRaw("DATE(check_in_date) as date, COUNT(*) as persons")
                ->from('forms')->whereNotNull('check_in_date')->groupBy('date')
                ->unionAll(
                    DB::table('family_booking')->selectRaw("DATE(check_in_date) as date, SUM(total_persons) as persons")
                        ->whereNotNull('check_in_date')->groupBy('date')
                )
                ->unionAll(
                    DB::table('group_bookings')->selectRaw("DATE(check_in_date) as date, SUM(total_persons) as persons")
                        ->whereNotNull('check_in_date')->groupBy('date')
                );
        }, 'all_checkins')
            ->select('date', DB::raw('SUM(persons) as checkin'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $checkouts = DB::table(function ($query) {
            $query->selectRaw("DATE(check_out_date) as date, COUNT(*) as persons")
                ->from('forms')->whereNotNull('check_out_date')->groupBy('date')
                ->unionAll(
                    DB::table('family_booking')->selectRaw("DATE(check_out_date) as date, SUM(total_persons) as persons")
                        ->whereNotNull('check_out_date')->groupBy('date')
                )
                ->unionAll(
                    DB::table('group_bookings')->selectRaw("DATE(check_out_date) as date, SUM(total_persons) as persons")
                        ->whereNotNull('check_out_date')->groupBy('date')
                );
        }, 'all_checkouts')
            ->select('date', DB::raw('SUM(persons) as checkout'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $report = collect();
        $staying = 0;
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $date = $start->toDateString();
            $checkin = $checkins[$date]->checkin ?? 0;
            $checkout = $checkouts[$date]->checkout ?? 0;
            $staying = $staying + $checkin - $checkout;

            $report->push([
                'date' => $date,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'staying' => $staying,
            ]);

            $start->addDay();
        }

        $pdf = Pdf::loadView('admin.reports.datewise-checkin-report-pdf', [
            'report' => $report,
            'from' => $from,
            'to' => $to,
        ]);

        return $pdf->download('checkin-report-' . now()->format('Ymd_His') . '.pdf');
    }
}
