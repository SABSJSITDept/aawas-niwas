<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomCategory;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DailyRoomReportController extends Controller
{
public function show(Request $request)
{
    $today = $request->input('date') ?? now()->toDateString();

    // Initialize counters
    $totalRooms = 0;
    $bookedRooms = 0;
    $totalCapacity = 0;
    $bookedCapacity = 0;

    $categories = RoomCategory::all();

    foreach ($categories as $category) {
        $roomNumbers = explode(',', $category->room_number);
        $roomCapacity = $category->total_capacity ?? 0;

        foreach ($roomNumbers as $room) {
            $room = trim($room);
            if (!$room) continue;

            $totalRooms++;
            $totalCapacity += $roomCapacity;

            $booked = BookedRoom::where('hotel_id', $category->hotel_id)
                ->whereRaw('LOWER(TRIM(room_number)) = ?', [strtolower($room)])
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
                ->sum('total_capacity');

            if ($booked > 0) {
                $bookedRooms++;
                $bookedCapacity += $booked;
            }
        }
    }

    $emptyRooms = max(0, $totalRooms - $bookedRooms);
    $availableCapacity = max(0, $totalCapacity - $bookedCapacity);

    // Today's check-ins / check-outs
    $checkInCount = Form::whereDate('check_in_date', $today)->count()
        + FamilyBooking::whereDate('check_in_date', $today)->count()
        + GroupBooking::whereDate('check_in_date', $today)->count();

    $checkOutCount = Form::whereDate('check_out_date', $today)->count()
        + FamilyBooking::whereDate('check_out_date', $today)->count()
        + GroupBooking::whereDate('check_out_date', $today)->count();

    // Detect if forms table has total_persons column (so we can sum correctly)
    $formHasPersons = false;
    try {
        if (\Illuminate\Support\Facades\Schema::hasColumn('forms', 'total_persons')) {
            $formHasPersons = true;
        }
    } catch (\Throwable $e) {
        // ignore schema check failures, default to false
    }

    // total guests present today (forms may represent single person or have total_persons)
    $totalGuestsForms = $formHasPersons
        ? Form::whereDate('check_in_date', '<=', $today)->whereDate('check_out_date', '>=', $today)->sum('total_persons')
        : Form::whereDate('check_in_date', '<=', $today)->whereDate('check_out_date', '>=', $today)->count();

    $totalGuests = $totalGuestsForms
        + FamilyBooking::whereDate('check_in_date', '<=', $today)->whereDate('check_out_date', '>=', $today)->sum('total_persons')
        + GroupBooking::whereDate('check_in_date', '<=', $today)->whereDate('check_out_date', '>=', $today)->sum('total_persons');

    // Not allotted total persons
    $notAllottedCount = 
    FamilyBooking::where('status', 'pending')
        ->whereDate('check_out_date', '>=', $today)
        ->sum('total_persons')
    +
    GroupBooking::where('status', 'pending')
        ->whereDate('check_out_date', '>=', $today)
        ->sum('total_persons');


    // Next 7 days — not allotted by check_in_date (persons)
    $start = Carbon::parse($today)->startOfDay();
    $notAllottedNext7 = [];
    for ($i = 0; $i < 7; $i++) {
        $date = $start->copy()->addDays($i)->toDateString();

        $familyPending = FamilyBooking::where('status', 'pending')->whereDate('check_in_date', $date)->sum('total_persons');
        $groupPending  = GroupBooking::where('status', 'pending')->whereDate('check_in_date', $date)->sum('total_persons');

        $notAllottedNext7[] = [
            'date' => $date,
            'family' => (int) $familyPending,
            'group'  => (int) $groupPending,
            'persons'=> (int) ($familyPending + $groupPending),
        ];
    }

    // Next 7 days — total stay/occupancy per day (people present that day)
    $staysNext7 = [];
    for ($i = 0; $i < 7; $i++) {
        $date = $start->copy()->addDays($i)->toDateString();

        // Forms: if forms have total_persons use sum else count
        $formsPresent = $formHasPersons
            ? Form::whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->sum('total_persons')
            : Form::whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->count();

        $familyPresent = FamilyBooking::whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->sum('total_persons');
        $groupPresent  = GroupBooking::whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->sum('total_persons');

        $totalStay = (int) ($formsPresent + $familyPresent + $groupPresent);

        $staysNext7[] = [
            'date' => $date,
            'forms' => (int) $formsPresent,
            'family' => (int) $familyPresent,
            'group' => (int) $groupPresent,
            'total' => $totalStay,
        ];
    }

    // Build allPending collection for table (combine family + group pending)
    $familyPendingList = FamilyBooking::where('status', 'pending')->get();
    $groupPendingList  = GroupBooking::where('status', 'pending')->get();

    $allPending = collect();
    foreach ($familyPendingList as $f) {
        $allPending->push((object)[
            'id' => $f->id,
            'name' => $f->name,
            'phone' => $f->phone,
            'total_persons' => $f->total_persons,
            'check_in_date' => $f->check_in_date,
            'check_out_date' => $f->check_out_date,
            'type' => 'Family'
        ]);
    }
    foreach ($groupPendingList as $g) {
        $allPending->push((object)[
            'id' => $g->id,
            'name' => $g->name,
            'phone' => $g->phone,
            'total_persons' => $g->total_persons,
            'check_in_date' => $g->check_in_date,
            'check_out_date' => $g->check_out_date,
            'type' => 'Group'
        ]);
    }

    // Sort by check_in_date ascending
    $allPending = $allPending->sortBy('check_in_date')->values();

    return view('admin.dashboard', compact(
        'totalRooms',
        'bookedRooms',
        'emptyRooms',
        'totalCapacity',
        'bookedCapacity',
        'availableCapacity',
        'totalGuests',
        'checkInCount',
        'checkOutCount',
        'notAllottedCount',
        'notAllottedNext7',
        'staysNext7',
        'allPending'
    ));
}




    public function download(Request $request)
    {
        $today = $request->input('date') ?? now()->toDateString();

        $totalRooms = 0;
        $bookedRooms = 0;
        $totalCapacity = 0;
        $bookedCapacity = 0;

        $categories = RoomCategory::all();

        foreach ($categories as $category) {
            $roomNumbers = explode(',', $category->room_number);
            $roomCapacity = $category->total_capacity ?? 0;

            foreach ($roomNumbers as $room) {
                $room = trim($room);
                if (!$room) continue;

                $totalRooms++;
                $totalCapacity += $roomCapacity;

                $booked = BookedRoom::where('hotel_id', $category->hotel_id)
                    ->whereRaw('LOWER(TRIM(room_number)) = ?', [strtolower($room)])
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
                    ->sum('total_capacity');

                if ($booked > 0) {
                    $bookedRooms++;
                    $bookedCapacity += $booked;
                }
            }
        }

        $emptyRooms = $totalRooms - $bookedRooms;
        $availableCapacity = $totalCapacity - $bookedCapacity;

        $checkInCount = Form::whereDate('check_in_date', $today)->count()
            + FamilyBooking::whereDate('check_in_date', $today)->count()
            + GroupBooking::whereDate('check_in_date', $today)->count();

        $checkOutCount = Form::whereDate('check_out_date', $today)->count()
            + FamilyBooking::whereDate('check_out_date', $today)->count()
            + GroupBooking::whereDate('check_out_date', $today)->count();

        $totalGuests = Form::whereDate('check_in_date', '<=', $today)
            ->whereDate('check_out_date', '>=', $today)->count()
            + FamilyBooking::whereDate('check_in_date', '<=', $today)
                ->whereDate('check_out_date', '>=', $today)
                ->sum('total_persons')
            + GroupBooking::whereDate('check_in_date', '<=', $today)
                ->whereDate('check_out_date', '>=', $today)
                ->sum('total_persons');

        $pdf = Pdf::loadView('admin.reports.daily-room-report-pdf', compact(
            'totalRooms',
            'bookedRooms',
            'emptyRooms',
            'totalCapacity',
            'bookedCapacity',
            'availableCapacity',
            'totalGuests',
            'checkInCount',
            'checkOutCount',
            'today'
        ));

        return $pdf->download('daily-room-report-' . $today . '.pdf');
    }

    public function notAllottedPdf()
    {
        $family = FamilyBooking::where('status', 'pending')->get();
        $group = GroupBooking::where('status', 'pending')->get();

        $all = [];

        foreach ($family as $f) {
            $all[] = [
                'booking_id' => $f->id + 100,
                'name' => $f->name,
                'phone' => $f->phone,
                'total_persons' => $f->total_persons,
                'check_in' => $f->check_in_date,
                'check_out' => $f->check_out_date,
                'type' => 'Family'
            ];
        }

        foreach ($group as $g) {
            $all[] = [
                'booking_id' => $g->id + 100,
                'name' => $g->name,
                'phone' => $g->phone,
                'total_persons' => $g->total_persons,
                'check_in' => $g->check_in_date,
                'check_out' => $g->check_out_date,
                'type' => 'Group'
            ];
        }

        $pdf = Pdf::loadView('admin.reports.not-allotted-pdf', [
            'data' => collect($all)
        ]);

        return $pdf->download('pending-room-not-allotted.pdf');
    }
}
