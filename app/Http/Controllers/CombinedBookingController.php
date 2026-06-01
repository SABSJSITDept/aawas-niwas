<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\Aanchal;
use App\Models\City;
use App\Models\BookedRoom;
use Illuminate\Support\Collection;

class CombinedBookingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 7;
        $page = $request->get('page', 1);

        // --- BUILD FAMILY QUERY ---
        $familyQuery = FamilyBooking::query()->with(['cityName','stateName','aanchalName']);
        $this->applyFilters($request, $familyQuery, false);

        // --- BUILD GROUP QUERY ---
        $groupQuery = GroupBooking::query();
        $this->applyFilters($request, $groupQuery, true);

        // Fetch collections
        $familyItems = $familyQuery->get()->map(function($b){
            return $this->mapUnifiedBooking($b, 'family');
        });

        $groupItems = $groupQuery->get()->map(function($b){
            return $this->mapUnifiedBooking($b, 'group');
        });

        // Merge, sort by original id desc (if you want by created_at use that field)
        $all = $familyItems->merge($groupItems)
                           ->sortByDesc('original_id')
                           ->values();

        // Manual pagination
        $total = $all->count();
        $itemsForCurrentPage = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        $aanchals = Aanchal::orderBy('display_order')->get();

        return view('bookings.combined', [
            'bookings' => $paginator,
            'aanchals' => $aanchals,
        ]);
    }

    /**
     * Show completed, rejected, and checked-out bookings
     */
    public function completedList(Request $request)
    {
        $statuses = ['completed', 'rejected', 'check-out'];
        $perPage = 10;
        $page = $request->get('page', 1);

        // Family bookings
        $familyQuery = FamilyBooking::query()->whereIn('status', $statuses);
        $familyItems = $familyQuery->get()->map(function($b){
            return $this->mapUnifiedBooking($b, 'family');
        });

        // Group bookings
        $groupQuery = GroupBooking::query()->whereIn('status', $statuses);
        $groupItems = $groupQuery->get()->map(function($b){
            return $this->mapUnifiedBooking($b, 'group');
        });

        $all = $familyItems->merge($groupItems)
                           ->sortByDesc('original_id')
                           ->values();
        $total = $all->count();
        $itemsForCurrentPage = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        return view('registration.completed_list', [
            'bookings' => $paginator
        ]);
    }

    // Apply common filters to query builder
    protected function applyFilters(Request $request, $query, $isGroup = false)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->filled('aadhar_number')) {
            $query->where('aadhar_number', 'like', "%{$request->aadhar_number}%");
        }

        if ($request->filled('city')) {
            $city = City::where('city_name', 'like', '%' . $request->city . '%')->first();
            if ($city) {
                $query->where('city', $city->city_id);
            } else {
                $query->where('city', 0);
            }
        }

        if ($request->filled('travel_type')) {
            $query->where('travel_type', $request->travel_type);
        }

        if ($request->filled('check_in_date')) {
            $query->where('check_in_date', $request->check_in_date);
        }

        if ($request->filled('check_out_date')) {
            $query->where('check_out_date', $request->check_out_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('aanchal')) {
            $query->where('aanchal', $request->aanchal);
        }

        if ($request->filled('is_veer_parivar')) {
            $query->where('is_veer_parivar', $request->is_veer_parivar);
        }

        // ID adjustment logic: user provides displayed id (id+100). We convert back.
        if ($isGroup) {
            // group controller used 'booking_id' earlier — accept both
            if ($request->filled('booking_id')) {
                $adjusted = (int)$request->booking_id - 100;
                $query->where('id', $adjusted);
            }
            if ($request->filled('id')) {
                $adjusted = (int)$request->id - 100;
                $query->where('id', $adjusted);
            }
        } else {
            if ($request->filled('id')) {
                $adjusted = (int)$request->id - 100;
                $query->where('id', $adjusted);
            }
            if ($request->filled('booking_id')) {
                $adjusted = (int)$request->booking_id - 100;
                $query->where('id', $adjusted);
            }
        }
    }

    // Map to unified structure and attach rooms info
    protected function mapUnifiedBooking($b, $type)
    {
        $rooms = BookedRoom::where('booking_id', $b->id)->get();

        $hotelName = optional($rooms->first()?->hotel)->hotel_name ?? null;
        $roomNumbers = $rooms->pluck('room_number')->toArray();

        // city / aanchal names fallback (if relations not loaded)
        $cityName = $b->cityName->city_name ?? (isset($b->city) ? \App\Models\City::find($b->city)?->city_name : null);
        $aanchalName = $b->aanchalName->name ?? (isset($b->aanchal) ? \App\Models\Aanchal::find($b->aanchal)?->name : null);

        return (object)[
            'type' => $type,                     // 'family' or 'group'
            'original_id' => $b->id,
            'display_id' => $b->id + 100,        // show to user as id + 100
            'name' => $b->name,
            'phone' => $b->phone,
            'aadhar_number' => $b->aadhar_number ?? null,
            'city' => $b->city,
            'city_name' => $cityName,
            'aanchal' => $b->aanchal,
            'aanchal_name' => $aanchalName,
            'check_in_date' => $b->check_in_date ?? null,
            'check_out_date' => $b->check_out_date ?? null,
            'status' => $b->status ?? null,
            'is_veer_parivar' => $b->is_veer_parivar ?? 0,
            'rooms_allotted' => $rooms->isNotEmpty(),
            'hotel_name' => $hotelName,
            'room_numbers' => $roomNumbers,
            'raw' => $b, // original model if you need links/actions
        ];
    }
}
