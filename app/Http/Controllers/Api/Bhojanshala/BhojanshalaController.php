<?php

namespace App\Http\Controllers\Api\Bhojanshala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class BhojanshalaController extends Controller
{
    /**
     * Return expected members for fixed slots 08:00, 13:00 and 18:00
     * considering only bookings with status = 'pending' or 'completed'.
     *
     * Request:
     *  - date (required) => 'YYYY-MM-DD'
     *
     * Response:
     *  {
     *    "date": "2025-10-03",
     *    "slots": {
     *      "08:00": { "total": 15, "family_booking": 10, "group_bookings": 5 },
     *      "13:00": { ... },
     *      "18:00": { ... }
     *    }
     *  }
     */
   public function expectedMembers(Request $request)
{
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $date = $request->input('date');

        $slots = [
            '08:00' => '07:30:00',
            '13:00' => '12:30:00',
            '18:00' => '17:00:00',
        ];

        $results = [];
        $hasFamilyStatus = Schema::hasColumn('family_booking', 'status');
        $hasGroupStatus = Schema::hasColumn('group_bookings', 'status');
        $hasFormsStatus = Schema::hasColumn('forms', 'status');
        $hasGroupTotalPersons = Schema::hasColumn('group_bookings', 'total_persons');
        $hasFormTotalPersons = Schema::hasColumn('forms', 'total_persons');

        foreach ($slots as $slot => $cutoff) {
            $slotDateTime = $date . ' ' . $slot . ':00';

            $familyQuery = DB::table('family_booking')
                ->selectRaw('COALESCE(SUM(total_persons),0) as s')
                ->when($hasFamilyStatus, function ($query) {
                    return $query->whereIn('status', ['pending', 'completed']);
                })
                ->whereRaw("CONCAT(check_in_date, ' ', COALESCE(check_in_time,'00:00:00')) <= ?", [$slotDateTime])
                ->whereRaw("CONCAT(check_out_date, ' ', COALESCE(check_out_time,'23:59:59')) >= ?", [$slotDateTime])
                ->whereRaw("NOT (check_in_date = ? AND COALESCE(check_in_time,'00:00:00') > ?)", [$date, $cutoff]);

            $familySum = (int) $familyQuery->value('s');

            $groupSelect = $hasGroupTotalPersons
                ? 'COALESCE(SUM(total_persons),0) as s'
                : 'COALESCE(SUM(COALESCE(total_persons, total_members + 1)),0) as s';

            $groupQuery = DB::table('group_bookings')
                ->selectRaw($groupSelect)
                ->when($hasGroupStatus, function ($query) {
                    return $query->whereIn('status', ['pending', 'completed']);
                })
                ->whereRaw("CONCAT(check_in_date, ' ', COALESCE(check_in_time,'00:00:00')) <= ?", [$slotDateTime])
                ->whereRaw("CONCAT(check_out_date, ' ', COALESCE(check_out_time,'23:59:59')) >= ?", [$slotDateTime])
                ->whereRaw("NOT (check_in_date = ? AND COALESCE(check_in_time,'00:00:00') > ?)", [$date, $cutoff]);

            $groupSum = (int) $groupQuery->value('s');

            $formSelect = $hasFormTotalPersons
                ? 'COALESCE(SUM(COALESCE(total_persons,1)),0) as s'
                : 'COALESCE(SUM(1),0) as s';

            $formQuery = DB::table('forms')
                ->selectRaw($formSelect)
                ->when($hasFormsStatus, function ($query) {
                    return $query->whereIn('status', ['pending', 'completed']);
                })
                ->whereRaw("CONCAT(check_in_date, ' ', COALESCE(check_in_time,'00:00:00')) <= ?", [$slotDateTime])
                ->whereRaw("CONCAT(check_out_date, ' ', COALESCE(check_out_time,'23:59:59')) >= ?", [$slotDateTime])
                ->whereRaw("NOT (check_in_date = ? AND COALESCE(check_in_time,'00:00:00') > ?)", [$date, $cutoff]);

            $formSum = (int) $formQuery->value('s');

            $total = $familySum + $groupSum + $formSum;

            $results[$slot] = [
                'total' => $total,
                'family_booking' => $familySum,
                'group_bookings' => $groupSum,
                'form_bookings' => $formSum,
            ];
        }

        return response()->json([
            'status' => 'success',
            'date' => $date,
            'slots' => $results,
        ]);
    }
}
