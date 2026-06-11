<?php

namespace App\Http\Controllers\Api\Registration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\FamilyMember;
use App\Models\GroupMember;
use App\Exports\RegistrationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Auto checkout bookings that have passed their checkout date
     */
    public function autoCheckout()
    {
        try {
            $now = now();
            $currentDate = $now->format('Y-m-d');
            $currentTime = $now->format('H:i:s');
            
            Log::info('Starting auto checkout process...');
            Log::info("Current Date: $currentDate, Current Time: $currentTime");
            
            // Get family bookings that need to be checked out
            $familyBookingsToCheckout = DB::table('family_booking')
                ->where(function($query) use ($currentDate, $currentTime) {
                    $query->where('check_out_date', '<', $currentDate)
                          ->orWhere(function($q) use ($currentDate, $currentTime) {
                              $q->where('check_out_date', '=', $currentDate)
                                ->where('check_out_time', '<', $currentTime);
                          });
                })
                ->where(function($query) {
                    $query->where('status', 'pending')
                          ->orWhere('status', 'completed')
                          ->orWhereNull('status');
                })
                ->get();
            
            Log::info('Found ' . $familyBookingsToCheckout->count() . ' family bookings to check out');

            // Process family bookings
            foreach($familyBookingsToCheckout as $booking) {
                try {
                    Log::info("Processing family booking - DB ID: {$booking->id}, Booking ID: {$booking->booking_id}");
                    Log::info("Checkout date: {$booking->check_out_date}, Checkout time: {$booking->check_out_time}");
                    
                    // Delete booked rooms entries by booking_id (F-XXX format)
                    $deletedRooms = DB::table('booked_rooms')->where('booking_id', $booking->booking_id)->delete();
                    Log::info("Deleted {$deletedRooms} booked room entries for booking {$booking->booking_id}");
                    
                    // Update booking status - using direct DB query to ensure it works
                    $updated = DB::table('family_booking')
                        ->where('id', $booking->id)
                        ->update(['status' => 'check-out']);
                    Log::info("Status update result for booking {$booking->id}: " . ($updated ? 'success' : 'failed'));
                    
                    if (!$updated) {
                        Log::warning("Failed to update status for family booking {$booking->id}. Current status: " . $booking->status);
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing family booking {$booking->id}: " . $e->getMessage());
                }
            }

            // Get group bookings that need to be checked out
            $groupBookingsToCheckout = DB::table('group_bookings')
                ->where(function($query) use ($currentDate, $currentTime) {
                    $query->where('check_out_date', '<', $currentDate)
                          ->orWhere(function($q) use ($currentDate, $currentTime) {
                              $q->where('check_out_date', '=', $currentDate)
                                ->where('check_out_time', '<', $currentTime);
                          });
                })
                ->where(function($query) {
                    $query->where('status', 'pending')
                          ->orWhere('status', 'completed')
                          ->orWhereNull('status');
                })
                ->get();

            Log::info('Found ' . $groupBookingsToCheckout->count() . ' group bookings to check out');
            
            // Process group bookings
            foreach($groupBookingsToCheckout as $booking) {
                try {
                    Log::info("Processing group booking - DB ID: {$booking->id}, Booking ID: {$booking->booking_id}");
                    Log::info("Checkout date: {$booking->check_out_date}, Checkout time: {$booking->check_out_time}");
                    
                    // Delete booked rooms entries by booking_id (G-XXX format)
                    $deletedRooms = DB::table('booked_rooms')->where('booking_id', $booking->booking_id)->delete();
                    Log::info("Deleted {$deletedRooms} booked room entries for booking {$booking->booking_id}");
                    
                    // Update booking status - using direct DB query to ensure it works
                    $updated = DB::table('group_bookings')
                        ->where('id', $booking->id)
                        ->update(['status' => 'check-out']);
                    Log::info("Status update result for booking {$booking->id}: " . ($updated ? 'success' : 'failed'));
                    
                    if (!$updated) {
                        Log::warning("Failed to update status for group booking {$booking->id}. Current status: " . $booking->status);
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing group booking {$booking->id}: " . $e->getMessage());
                }
            }
                       
            Log::info('Auto checkout completed successfully for date: ' . $currentDate . ' time: ' . $currentTime);
            return true;
        } catch (\Exception $e) {
            Log::error('Auto checkout failed: ' . $e->getMessage());
            return false;
        }
    }

    private function applyDynamicFilters($familyQuery, $groupQuery, Request $request)
    {
        $filterableColumns = [
            'name', 'father_name', 'phone', 'aadhar_number', 'age', 'gender', 'ms_name', 'mid',
            'city', 'state', 'aanchal', 'travel_type', 'check_in_date', 'check_in_time', 
            'check_out_date', 'check_out_time', 'family_coming', 'no_of_people', 'no_of_children',
            'total_male', 'total_female', 'sixty_plus_members', 'sixty_plus_male', 'sixty_plus_female',
            'is_veer_parivar', 'remark'
        ];

        foreach ($filterableColumns as $column) {
            $value = $request->get("filter_{$column}");
            if ($value !== null && $value !== '') {
                $exactMatchCols = ['age', 'no_of_people', 'no_of_children', 'total_male', 'total_female', 'sixty_plus_members', 'sixty_plus_male', 'sixty_plus_female', 'check_in_date', 'check_out_date', 'check_in_time', 'check_out_time', 'gender', 'travel_type', 'family_coming', 'is_veer_parivar', 'mid', 'aanchal'];
                if (in_array($column, $exactMatchCols)) {
                    $familyQuery->where($column, $value);
                    $groupQuery->where($column, $value);
                } else {
                    if ($column === 'city') {
                        $familyQuery->where(function($q) use ($column, $value) {
                            $q->where($column, 'like', "%{$value}%")
                              ->orWhereHas('cityName', function($q2) use ($value) {
                                  $q2->where('city_name', 'like', "%{$value}%");
                              });
                        });
                        $groupQuery->where(function($q) use ($column, $value) {
                            $q->where($column, 'like', "%{$value}%")
                              ->orWhereHas('cityName', function($q2) use ($value) {
                                  $q2->where('city_name', 'like', "%{$value}%");
                              });
                        });
                    } elseif ($column === 'state') {
                        $familyQuery->where(function($q) use ($column, $value) {
                            $q->where($column, 'like', "%{$value}%")
                              ->orWhereHas('stateName', function($q2) use ($value) {
                                  $q2->where('state_name', 'like', "%{$value}%");
                              });
                        });
                        $groupQuery->where(function($q) use ($column, $value) {
                            $q->where($column, 'like', "%{$value}%")
                              ->orWhereHas('stateName', function($q2) use ($value) {
                                  $q2->where('state_name', 'like', "%{$value}%");
                              });
                        });
                    } else {
                        $familyQuery->where($column, 'like', "%{$value}%");
                        $groupQuery->where($column, 'like', "%{$value}%");
                    }
                }
            }
        }

        // Apply filters for dynamic extra fields
        $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
        foreach ($dynamicFields as $df) {
            $value = $request->get("filter_extra_{$df->name}");
            if ($value !== null && $value !== '') {
                // Laravel 5.7+ JSON where syntax: where('column->key', 'value')
                $familyQuery->where("extra_fields->{$df->name}", 'like', "%{$value}%");
                $groupQuery->where("extra_fields->{$df->name}", 'like', "%{$value}%");
            }
        }

        $filter_type = $request->get('filter_type');
        if ($filter_type === 'family') {
            $groupQuery->whereRaw('1 = 0');
        } elseif ($filter_type === 'group') {
            $familyQuery->whereRaw('1 = 0');
        }

        $booking_id = $request->get('booking_id') ?? $request->get('filter_booking_id');
        if ($booking_id) {
            $normalized = trim($booking_id);
            if (str_starts_with($normalized, 'F-') || str_starts_with($normalized, 'f-')) {
                $num = (int) substr($normalized, 2);
                $familyQuery->where('id', $num - 100);
                $groupQuery->whereRaw('1 = 0');
            } elseif (str_starts_with($normalized, 'G-') || str_starts_with($normalized, 'g-')) {
                $num = (int) substr($normalized, 2);
                $groupQuery->where('id', $num - 100);
                $familyQuery->whereRaw('1 = 0');
            } else {
                $num = (int) $normalized;
                $familyQuery->where('id', $num - 100);
                $groupQuery->where('id', $num - 100);
            }
        }

        $search = $request->get('search');
        if ($search) {
            $familyQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('aadhar_number', 'like', "%$search%");
            });
            $groupQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('aadhar_number', 'like', "%$search%");
            });
        }
    }

    /**
     * Return merged list of pending bookings from family_booking and group_bookings
     * Returns JSON with manual pagination (so we can merge two different table structures)
     */
  public function index(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $page = max(1, (int) $request->get('page', 1));

    // Base queries (only pending) - with relationships for city/state/aanchal names
    $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');
    $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');

    $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

    // Fetch and convert to arrays (include all model attributes)
    // Fetch and convert to arrays (include all model attributes)
 $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
    $arr = $b->toArray();
    $arr['type'] = 'family';
    $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
    // Add city/state/aanchal names from relationships
    $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
    $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
    $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
    // remove embedding raw model to avoid surprises:
    // $arr['raw'] = $b;
    return $arr;
 } )->values()->toBase(); // <-- ensure this is a base Support\Collection of arrays

 $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
    $arr = $b->toArray();
    $arr['type'] = 'group';
    $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
    // Add city/state/aanchal names from relationships
    $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
    $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
    $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
    // $arr['raw'] = $b;
    return $arr;
 })->values()->toBase(); // <-- ensure this is a base Support\Collection of arrays

 // Merge and sort (by a stable key mixing type and id)
 // now both $families and $groups are Support\Collection so merge is safe
 $merged = $families->merge($groups)
    ->sortByDesc(function($item){
        return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
    })->values();


    // Manual pagination
    $total = $merged->count();
    $sliced = $merged->slice(($page -1) * $perPage, $perPage)->values();

    return response()->json([
        'data' => $sliced,
        'meta' => [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ]
    ]);
}

    /**
     * Show full details (view more) including members for given type and id
     * type = family|group
     */
    public function show($type, $id)
    {
        if ($type === 'family') {
            $booking = FamilyBooking::with('familyMembers')->findOrFail($id);
            return response()->json(['type' => 'family', 'booking' => $booking]);
        }

        if ($type === 'group') {
            $booking = GroupBooking::with('groupMembers')->findOrFail($id);
            return response()->json(['type' => 'group', 'booking' => $booking]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }

    /**
     * Update either family or group booking
     */
    /**
     * Update either family or group booking — update all editable fields.
     */
    public function update(Request $request, $type, $id)
    {
        if ($type === 'family') {
            $allowed = [
                'name','father_name','phone','aadhar_number','age','mid','ms_name',
                'city','state','aanchal','travel_type',
                'check_in_date','check_in_time','check_out_date','check_out_time',
                'family_coming','no_of_children','total_persons',
                'total_male','total_female',
                'sixty_plus_members','sixty_plus_male','sixty_plus_female',
                'is_veer_parivar','veer_relation'
            ];

            $rules = [
                'name' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'aadhar_number' => "nullable|string|size:12|unique:family_booking,aadhar_number,{$id}",
                'age' => 'nullable|integer|min:0|max:120',
                'mid' => 'nullable|string|max:20',
                'ms_name' => 'nullable|string|max:255',
                'city' => 'nullable',
                'state' => 'nullable',
                'aanchal' => 'nullable',
                'travel_type' => 'nullable|string|max:50',
                'check_in_date' => 'nullable|date',
                'check_in_time' => 'nullable',
                'check_out_date' => 'nullable|date|after_or_equal:check_in_date',
                'check_out_time' => 'nullable',
                'family_coming' => 'nullable|in:0,1',
                'no_of_children' => 'nullable|integer|min:0',
                'total_persons' => 'nullable|integer|min:1',
                'total_male' => 'nullable|integer|min:0',
                'total_female' => 'nullable|integer|min:0',
                'sixty_plus_members' => 'nullable|integer|min:0',
                'sixty_plus_male' => 'nullable|integer|min:0',
                'sixty_plus_female' => 'nullable|integer|min:0',
                'is_veer_parivar' => 'nullable|in:0,1',
                'veer_relation' => 'nullable|string|max:255',
            ];

            $v = Validator::make($request->all(), $rules);
            if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

            $booking = FamilyBooking::findOrFail($id);
            $data = array_intersect_key($request->all(), array_flip($allowed));
            
            $booking->fill($data);
            $booking->save();

            return response()->json(['success' => true, 'booking' => $booking]);
        }

        if ($type === 'group') {
            $allowed = [
                'name','father_name','phone','aadhar_number','mid',
                'city','state','aanchal','travel_type',
                'check_in_date','check_in_time','check_out_date','check_out_time',
                'total_persons','total_members','total_male','total_female',
                'sixty_plus_members','sixty_plus_male','sixty_plus_female','child_count'
            ];

            $rules = [
                'name' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'aadhar_number' => "nullable|string|size:12|unique:group_bookings,aadhar_number,{$id}",
                'mid' => 'nullable|string|max:20',
                'city' => 'nullable',
                'state' => 'nullable',
                'aanchal' => 'nullable',
                'travel_type' => 'nullable|string|max:50',
                'check_in_date' => 'nullable|date',
                'check_in_time' => 'nullable',
                'check_out_date' => 'nullable|date|after_or_equal:check_in_date',
                'check_out_time' => 'nullable',
                'total_persons' => 'nullable|integer|min:1',
                'total_members' => 'nullable|integer|min:0',
                'total_male' => 'nullable|integer|min:0',
                'total_female' => 'nullable|integer|min:0',
                'sixty_plus_members' => 'nullable|integer|min:0',
                'sixty_plus_male' => 'nullable|integer|min:0',
                'sixty_plus_female' => 'nullable|integer|min:0',
                'no_of_children' => 'nullable|integer|min:0',
            ];

            $v = Validator::make($request->all(), $rules);
            if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

            $booking = GroupBooking::findOrFail($id);

            // Handle mapping of no_of_children from form to child_count in group_bookings table
            $allData = $request->all();
            if (array_key_exists('no_of_children', $allData)) {
                $allData['child_count'] = $allData['no_of_children'];
            }

            $data = array_intersect_key($allData, array_flip($allowed));

            if (!isset($data['total_members']) && isset($data['total_persons'])) {
                // $data['total_members'] = max(0, (int)$data['total_persons'] - 1);
            }

            $booking->fill($data);
            $booking->save();

            return response()->json(['success' => true, 'booking' => $booking]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }


    /**
     * Delete booking and its members
     */
    public function destroy($type, $id)
    {
        if ($type === 'family') {
            FamilyMember::where('family_id', $id)->delete();
            FamilyBooking::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        }

        if ($type === 'group') {
            GroupMember::where('group_booking_id', $id)->delete();
            GroupBooking::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }

    /**
     * Quick action: change status (eg: approve/reject/checkout)
     */
    public function changeStatus(Request $request, $type, $id)
    {
        $request->validate(['status' => 'required|string']);
        $status = $request->status;

        if ($type === 'family') {
            $booking = FamilyBooking::findOrFail($id);
            $booking->status = $status;
            $booking->save();
            // update members too if needed
            FamilyMember::where('family_id', $id)->update(['status' => $status]);
            return response()->json(['success' => true, 'booking' => $booking]);
        }

        if ($type === 'group') {
            $booking = GroupBooking::findOrFail($id);
            $booking->status = $status;
            $booking->save();
            GroupMember::where('group_booking_id', $id)->update(['status' => $status]);
            return response()->json(['success' => true, 'booking' => $booking]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }

    /**
     * Clear room allocation for a given booking and change status back to pending
     */
    public function clearRoom(Request $request, $type, $id)
    {
        try {
            DB::beginTransaction();

            $bookingModel = null;
            if ($type === 'family') {
                $bookingModel = \App\Models\FamilyBooking::find($id);
            } elseif ($type === 'group') {
                $bookingModel = \App\Models\GroupBooking::find($id);
            } elseif ($type === 'vip') {
                $bookingModel = \App\Models\Form::find($id);
            }

            if (!$bookingModel) {
                return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
            }

            $displayBookingId = $bookingModel->booking_id ?? $id;

            // Get all rooms booked by this booking
            $bookedRooms = \App\Models\BookedRoom::where('booking_id', $displayBookingId)->get();

            foreach ($bookedRooms as $bookedRoom) {
                // Restore capacity in RoomStatus
                $status = \App\Models\RoomStatus::where('hotel_id', $bookedRoom->hotel_id)
                    ->where('room_number', $bookedRoom->room_number)
                    ->first();
                if ($status) {
                    $status->available_capacity += $bookedRoom->total_capacity;
                    $status->status = $status->available_capacity <= 0 ? 'Full' : 'Partial';
                    $status->save();
                }
                $bookedRoom->delete();
            }

            // Also delete by pure ID just in case
            \App\Models\BookedRoom::where('booking_id', $id)->delete();

            // Revert status to pending
            $bookingModel->status = 'pending';
            $bookingModel->save();

            if ($bookingModel instanceof \App\Models\FamilyBooking) {
                \App\Models\FamilyMember::where('family_id', $bookingModel->id)->update(['status' => 'pending']);
            } elseif ($bookingModel instanceof \App\Models\GroupBooking) {
                \App\Models\GroupMember::where('group_booking_id', $bookingModel->id)->update(['status' => 'pending']);
            }

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Clear room error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to clear room allocation.'], 500);
        }
    }

      private function getMergedDataForExport(Request $request)
    {
        // copy the same filters/merge logic from your index()
        // Return a Collection of arrays (not Eloquent models) so export is simple.

        // We'll reuse your index() logic but return full arrays
        $perPage = 1000000; // large, for export
        $page = 1;

        $familyQuery = \App\Models\FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');
        $groupQuery = \App\Models\GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');

        $this->applyDynamicFilters($familyQuery, $groupQuery, $request);
$families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
    $arr = $b->toArray();
    $arr['type'] = 'family';
    $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
    // Add city/state/aanchal names from relationships
    $arr['city_name'] = $b->cityName?->city_name ?? ($b->city ?? '');
    $arr['state_name'] = $b->stateName?->state_name ?? ($b->state ?? '');
    $arr['aanchal_name'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
    return $arr;
})->values()->toBase();

$groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
    $arr = $b->toArray();
    $arr['type'] = 'group';
    $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
    // Add city/state/aanchal names from relationships
    $arr['city_name'] = $b->cityName?->city_name ?? ($b->city ?? '');
    $arr['state_name'] = $b->stateName?->state_name ?? ($b->state ?? '');
    $arr['aanchal_name'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
    return $arr;
})->values()->toBase();

$merged = $families->merge($groups)->sortByDesc(function($item){
    return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
})->values();


        return $merged;
    }

    /**
     * Export merged registrations as Excel or PDF
     * GET /api/registration/export?format=excel|pdf&search=...&booking_id=...
     */
    public function export(Request $request)
    {
        try {
            $format = strtolower($request->get('format', 'excel')); // default excel
            
            Log::info('Registration export started', [
                'format' => $format,
                'search' => $request->get('search'),
                'booking_id' => $request->get('booking_id')
            ]);
            
            $data = $this->getMergedDataForExport($request); // Collection of arrays

            $visibleColumnsKeys = $request->get('visible_columns');
            $allColumnsMap = [
                'type' => 'Type',
                'booking_id' => 'Booking ID',
                'name' => 'Name',
                'father_name' => 'Father Name',
                'phone' => 'Phone',
                'aadhar_number' => 'Aadhar Number',
                'age' => 'Age',
                'mid' => 'MID',                
                'city' => 'City',
                'state' => 'State',
                'aanchal' => 'Aanchal',
                'travel_type' => 'Travel Type',
                'check_in_date' => 'Check-in Date',
                'check_in_time' => 'Check-in Time',
                'check_out_date' => 'Check-out Date',
                'check_out_time' => 'Check-out Time',
                'total_persons' => 'Total Persons'
            ];

            // Fetch dynamic fields
            $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
            foreach ($dynamicFields as $df) {
                $allColumnsMap['extra_' . $df->name] = $df->label;
            }

            // If no visible columns sent, default to all
            if (empty($visibleColumnsKeys) || !is_array($visibleColumnsKeys)) {
                $visibleColumnsKeys = array_keys($allColumnsMap);
            }

            $exportHeadings = [];
            foreach ($visibleColumnsKeys as $key) {
                if (isset($allColumnsMap[$key])) {
                    $exportHeadings[] = $allColumnsMap[$key];
                }
            }

            // prepare flat rows — pick only the columns you specified
            // Ensure all strings are properly UTF-8 encoded
            $rows = $data->map(function($row) use ($visibleColumnsKeys, $dynamicFields) {
                $ensureUtf8 = function($value) {
                    if (!is_string($value)) return $value;
                    // Check if already UTF-8, if not convert
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                    }
                    return $value;
                };
                
                $fullRow = [
                    'type' => $ensureUtf8($row['type'] ?? ''),
                    'booking_id' => $ensureUtf8($row['booking_id'] ?? ''),
                    'name' => $ensureUtf8($row['name'] ?? ''),
                    'father_name' => $ensureUtf8($row['father_name'] ?? ''),
                    'phone' => $ensureUtf8($row['phone'] ?? ''),
                    'aadhar_number' => $ensureUtf8($row['aadhar_number'] ?? ''),
                    'age' => $ensureUtf8($row['age'] ?? ''),
                    'mid' => $ensureUtf8($row['mid'] ?? ''),                
                    'city' => $ensureUtf8($row['city_name'] ?? ($row['city'] ?? '')),
                    'state' => $ensureUtf8($row['state_name'] ?? ($row['state'] ?? '')),
                    'aanchal' => $ensureUtf8($row['aanchal_name'] ?? ($row['aanchal'] ?? '')),
                    'travel_type' => $ensureUtf8($row['travel_type'] ?? ''),
                    'check_in_date' => $ensureUtf8($row['check_in_date'] ?? ''),
                    'check_in_time' => $ensureUtf8($row['check_in_time'] ?? ''),
                    'check_out_date' => $ensureUtf8($row['check_out_date'] ?? ''),
                    'check_out_time' => $ensureUtf8($row['check_out_time'] ?? ''),
                    'total_persons' => $ensureUtf8($row['total_persons'] ?? '')
                ];

                $extraData = $row['extra_fields'] ?? [];
                if (is_string($extraData)) {
                    $extraData = json_decode($extraData, true) ?? [];
                }
                foreach ($dynamicFields as $df) {
                    $fullRow['extra_' . $df->name] = $ensureUtf8($extraData[$df->name] ?? '');
                }

                $filteredRow = [];
                foreach ($visibleColumnsKeys as $key) {
                    if (isset($fullRow[$key])) {
                        $filteredRow[$key] = $fullRow[$key];
                    }
                }
                return $filteredRow;
            })->toArray();

        if ($format === 'excel') {
            // Excel export using proper export class
            $filename = 'registrations-' . now()->format('Ymd-His') . '.xlsx';
            $export = new RegistrationExport($rows, $exportHeadings);
            
            Log::info('Excel export completed', ['filename' => $filename, 'rows' => count($rows)]);
            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
        }

        // PDF with image-based rendering (for perfect Hindi support)
        try {
            $pdfData = $rows;
            
            // Generate styled HTML table
            $htmlTable = $this->generateStyledTableHtml($pdfData);
            
            // Convert HTML table to PNG image using Browsershot
            $imagePath = $this->convertHtmlTableToImage($htmlTable);
            
            // Create PDF with the image embedded
            $pdf = Pdf::loadView('exports.registrations_pdf_image', compact('imagePath'));
            $filename = 'registrations-' . now()->format('Ymd-His') . '.pdf';
            
            Log::info('PDF export with image completed', ['filename' => $filename, 'rows' => count($rows)]);
            return $pdf->download($filename);
            
        } catch (\Exception $imageError) {
            Log::warning('Image-based PDF failed, falling back to standard PDF', [
                'error' => $imageError->getMessage()
            ]);
            
            // Fallback to standard PDF if image generation fails
            $pdfData = $rows;
            $pdf = Pdf::loadView('exports.registrations_pdf', compact('pdfData'));
            $pdf->setOptions([
                'defaultFont' => 'dejavusans',
                'dpi' => 96,
                'enable_font_subsetting' => false,
                'enable_css_float' => true,
                'enable_php' => false,
                'enable_remote' => false,
                'font_dir' => storage_path('fonts/'),
                'font_cache' => storage_path('fonts/'),
                'logOutputFile' => storage_path('logs/dompdf.log'),
            ]);
            $filename = 'registrations-' . now()->format('Ymd-His') . '.pdf';
            
            Log::info('PDF export completed (fallback)', ['filename' => $filename, 'rows' => count($rows)]);
            return $pdf->download($filename);
        }
        
        } catch (\Exception $e) {
            Log::error('Registration export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get completed, rejected, check-out bookings (for admin table)
     */
    public function completedList(Request $request)
    {
        $statuses = ['completed', 'rejected', 'check-out'];
        $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
        $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

        $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $display_id = !empty($b->booking_id) ? $b->booking_id : 'F-' . ($b->id + 100);
            $arr['display_id'] = $display_id;
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            unset($arr['city_name'], $arr['state_name'], $arr['aanchal_name']);

            $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
            if ($rooms->isNotEmpty()) {
                $grouped = [];
                foreach($rooms as $rm) {
                    $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                    $grouped[$hotelName][] = $rm->room_number;
                }
                $allotmentParts = [];
                foreach($grouped as $hName => $rNums) {
                    $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                }
                $arr['allotment_info'] = implode(' | ', $allotmentParts);
            } else {
                $arr['allotment_info'] = 'Not Allotted';
            }

            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $display_id = !empty($b->booking_id) ? $b->booking_id : 'G-' . ($b->id + 100);
            $arr['display_id'] = $display_id;
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            unset($arr['city_name'], $arr['state_name'], $arr['aanchal_name']);

            $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
            if ($rooms->isNotEmpty()) {
                $grouped = [];
                foreach($rooms as $rm) {
                    $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                    $grouped[$hotelName][] = $rm->room_number;
                }
                $allotmentParts = [];
                foreach($grouped as $hName => $rNums) {
                    $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                }
                $arr['allotment_info'] = implode(' | ', $allotmentParts);
            } else {
                $arr['allotment_info'] = 'Not Allotted';
            }

            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        $perPage = (int) $request->get('per_page', 25);
        $page = max(1, (int) $request->get('page', 1));
        
        $total = $merged->count();
        $sliced = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $sliced,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / max(1, $perPage)) ?: 1,
            ]
        ]);
    }

    public function allList(Request $request)
    {
        $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName']);
        $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName']);

        $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $display_id = !empty($b->booking_id) ? $b->booking_id : 'F-' . ($b->id + 100);
            $arr['display_id'] = $display_id;
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            unset($arr['city_name'], $arr['state_name'], $arr['aanchal_name']);

            $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
            if ($rooms->isNotEmpty()) {
                $grouped = [];
                foreach($rooms as $rm) {
                    $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                    $grouped[$hotelName][] = $rm->room_number;
                }
                $allotmentParts = [];
                foreach($grouped as $hName => $rNums) {
                    $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                }
                $arr['allotment_info'] = implode(' | ', $allotmentParts);
            } else {
                $arr['allotment_info'] = 'Not Allotted';
            }

            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $display_id = !empty($b->booking_id) ? $b->booking_id : 'G-' . ($b->id + 100);
            $arr['display_id'] = $display_id;
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            unset($arr['city_name'], $arr['state_name'], $arr['aanchal_name']);

            $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
            if ($rooms->isNotEmpty()) {
                $grouped = [];
                foreach($rooms as $rm) {
                    $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                    $grouped[$hotelName][] = $rm->room_number;
                }
                $allotmentParts = [];
                foreach($grouped as $hName => $rNums) {
                    $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                }
                $arr['allotment_info'] = implode(' | ', $allotmentParts);
            } else {
                $arr['allotment_info'] = 'Not Allotted';
            }

            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        $perPage = (int) $request->get('per_page', 25);
        $page = max(1, (int) $request->get('page', 1));
        
        $total = $merged->count();
        $sliced = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $sliced,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / max(1, $perPage)) ?: 1,
            ]
        ]);
    }

    /**
     * Export completed registrations to Excel or PDF
     * GET /api/registration/completed-export?format=excel|pdf
     */
    public function completedExport(Request $request)
    {
        try {
            $format = strtolower($request->get('format', 'excel'));
            
            Log::info('Completed registration export started', [
                'format' => $format
            ]);
            
            // Get only completed bookings data
            $statuses = ['completed'];
            $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
            $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

            $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

            $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'family';
                $display_id = !empty($b->booking_id) ? $b->booking_id : 'F-' . ($b->id + 100);
                $arr['booking_id'] = $display_id;
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';

                $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
                if ($rooms->isNotEmpty()) {
                    $grouped = [];
                    foreach($rooms as $rm) {
                        $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                        $grouped[$hotelName][] = $rm->room_number;
                    }
                    $allotmentParts = [];
                    foreach($grouped as $hName => $rNums) {
                        $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                    }
                    $arr['allotment_info'] = implode(' | ', $allotmentParts);
                } else {
                    $arr['allotment_info'] = 'Not Allotted';
                }
                
                return $arr;
            })->values()->toBase();

            $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'group';
                $display_id = !empty($b->booking_id) ? $b->booking_id : 'G-' . ($b->id + 100);
                $arr['booking_id'] = $display_id;
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';

                $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
                if ($rooms->isNotEmpty()) {
                    $grouped = [];
                    foreach($rooms as $rm) {
                        $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                        $grouped[$hotelName][] = $rm->room_number;
                    }
                    $allotmentParts = [];
                    foreach($grouped as $hName => $rNums) {
                        $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                    }
                    $arr['allotment_info'] = implode(' | ', $allotmentParts);
                } else {
                    $arr['allotment_info'] = 'Not Allotted';
                }

                return $arr;
            })->values()->toBase();

            $data = $families->merge($groups);

            $visibleColumnsKeys = $request->get('visible_columns');
            $allColumnsMap = [
                'type' => 'Type',
                'booking_id' => 'Booking ID',
                'name' => 'Name',
                'father_name' => 'Father Name',
                'phone' => 'Phone',
                'aadhar_number' => 'Aadhar Number',
                'age' => 'Age',
                'mid' => 'MID',                
                'city' => 'City',
                'state' => 'State',
                'city_state' => 'City & State',
                'aanchal' => 'Aanchal',
                'travel_type' => 'Travel Type',
                'check_in_date' => 'Check-in Date',
                'check_out_date' => 'Check-out Date',
                'check_in_out' => 'Check In/Out',
                'total_persons' => 'Total Persons',
                'allotment_info' => 'Allotment Info'
            ];

            $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
            foreach ($dynamicFields as $df) {
                $allColumnsMap['extra_' . $df->name] = $df->label;
            }

            if (empty($visibleColumnsKeys) || !is_array($visibleColumnsKeys)) {
                $visibleColumnsKeys = array_keys($allColumnsMap);
            }

            $exportHeadings = [];
            foreach ($visibleColumnsKeys as $key) {
                if (isset($allColumnsMap[$key])) {
                    $exportHeadings[] = $allColumnsMap[$key];
                }
            }

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row) use ($visibleColumnsKeys) {
                $c = $row['city'] ?? '';
                $s = $row['state'] ?? '';
                $cityState = ($c && $s) ? "$c, $s" : ($c ?: ($s ?: ''));
                
                $ciDate = $row['check_in_date'] ?? '';
                $ciTime = $row['check_in_time'] ?? '';
                $coDate = $row['check_out_date'] ?? '';
                $coTime = $row['check_out_time'] ?? '';
                $ci = $ciDate . ($ciTime ? ' ' . $ciTime : '');
                $co = $coDate . ($coTime ? ' ' . $coTime : '');
                $checkInOut = "In: $ci\nOut: $co";

                $fullRow = [
                    'type' => $row['type'] ?? '',
                    'booking_id' => $row['booking_id'] ?? '',
                    'name' => $row['name'] ?? '',
                    'father_name' => $row['father_name'] ?? '',
                    'phone' => $row['phone'] ?? '',
                    'aadhar_number' => $row['aadhar_number'] ?? '',
                    'age' => $row['age'] ?? '',
                    'mid' => $row['mid'] ?? '',                    
                    'city' => $row['city'] ?? '',
                    'state' => $row['state'] ?? '',
                    'city_state' => $cityState,
                    'aanchal' => $row['aanchal'] ?? '',
                    'travel_type' => $row['travel_type'] ?? '',
                    'check_in_date' => $row['check_in_date'] ?? '',
                    'check_out_date' => $row['check_out_date'] ?? '',
                    'check_in_out' => $checkInOut,
                    'total_persons' => $row['total_persons'] ?? '',
                    'allotment_info' => $row['allotment_info'] ?? ''
                ];
                
                $extraData = $row['extra_fields'] ?? [];
                if (is_string($extraData)) {
                    $extraData = json_decode($extraData, true) ?? [];
                }
                foreach ($dynamicFields as $df) {
                    $fullRow['extra_' . $df->name] = $extraData[$df->name] ?? '';
                }
                
                $filteredRow = [];
                foreach ($visibleColumnsKeys as $key) {
                    if (isset($fullRow[$key])) {
                        $filteredRow[$key] = $fullRow[$key];
                    }
                }
                return $filteredRow;
            })->toArray();

            if ($format === 'excel') {
                $filename = 'completed-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows, $exportHeadings);
                
                Log::info('Completed Excel export completed', ['filename' => $filename, 'rows' => count($rows)]);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
            }

            // PDF export
            $pdfData = $rows;
            $pdf = Pdf::loadView('exports.completed_registrations_pdf', compact('pdfData'));
            $filename = 'completed-registrations-' . now()->format('Ymd-His') . '.pdf';
            
            Log::info('Completed PDF export completed', ['filename' => $filename, 'rows' => count($rows)]);
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Completed registration export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get only rejected and check-out bookings
     */
    public function rejectedCheckoutList(Request $request)
    {
        $statuses = ['rejected', 'check-out'];
        $familyQuery = FamilyBooking::whereIn('status', $statuses);
        $groupQuery = GroupBooking::whereIn('status', $statuses);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $arr['display_id'] = 'F-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $arr['display_id'] = 'G-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        return response()->json(['data' => $merged]);
    }

    /**
     * Export rejected and check-out registrations to Excel or PDF
     * GET /api/registration/rejected-export?format=excel|pdf
     */
    public function rejectedExport(Request $request)
    {
        try {
            $format = strtolower($request->get('format', 'excel'));
            
            Log::info('Rejected registration export started', [
                'format' => $format
            ]);
            
            // Get only rejected bookings data
            $statuses = ['rejected'];
            $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
            $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

            // Add search filters
            $search = $request->get('search');
            if ($search) {
                $familyQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
                $groupQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            }

            $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'family';
                $arr['booking_id'] = 'F-' . ($b->id + 100);
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';
                return $arr;
            })->values()->toBase();

            $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'group';
                $arr['booking_id'] = 'G-' . ($b->id + 100);
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';
                return $arr;
            })->values()->toBase();

            $data = $families->merge($groups);

            $visibleColumnsKeys = $request->get('visible_columns');
            $allColumnsMap = [
                'type' => 'Type',
                'booking_id' => 'Booking ID',
                'name' => 'Name',
                'father_name' => 'Father Name',
                'phone' => 'Phone',
                'aadhar_number' => 'Aadhar Number',
                'age' => 'Age',
                'mid' => 'MID',                
                'city' => 'City',
                'state' => 'State',
                'aanchal' => 'Aanchal',
                'travel_type' => 'Travel Type',
                'check_in_date' => 'Check-in Date',
                'check_out_date' => 'Check-out Date',
                'total_persons' => 'Total Persons'
            ];

            $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
            foreach ($dynamicFields as $df) {
                $allColumnsMap['extra_' . $df->name] = $df->label;
            }

            if (empty($visibleColumnsKeys) || !is_array($visibleColumnsKeys)) {
                $visibleColumnsKeys = array_keys($allColumnsMap);
            }

            $exportHeadings = [];
            foreach ($visibleColumnsKeys as $key) {
                if (isset($allColumnsMap[$key])) {
                    $exportHeadings[] = $allColumnsMap[$key];
                }
            }

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row) use ($visibleColumnsKeys, $dynamicFields) {
                $fullRow = [
                    'type' => $row['type'] ?? '',
                    'booking_id' => $row['booking_id'] ?? '',
                    'name' => $row['name'] ?? '',
                    'father_name' => $row['father_name'] ?? '',
                    'phone' => $row['phone'] ?? '',
                    'aadhar_number' => $row['aadhar_number'] ?? '',
                    'age' => $row['age'] ?? '',
                    'mid' => $row['mid'] ?? '',                    
                    'city' => $row['city'] ?? '',
                    'state' => $row['state'] ?? '',
                    'aanchal' => $row['aanchal'] ?? '',
                    'travel_type' => $row['travel_type'] ?? '',
                    'check_in_date' => $row['check_in_date'] ?? '',
                    'check_out_date' => $row['check_out_date'] ?? '',
                    'total_persons' => $row['total_persons'] ?? ''
                ];
                
                $extraData = $row['extra_fields'] ?? [];
                if (is_string($extraData)) {
                    $extraData = json_decode($extraData, true) ?? [];
                }
                foreach ($dynamicFields as $df) {
                    $fullRow['extra_' . $df->name] = $extraData[$df->name] ?? '';
                }
                
                $filteredRow = [];
                foreach ($visibleColumnsKeys as $key) {
                    if (isset($fullRow[$key])) {
                        $filteredRow[$key] = $fullRow[$key];
                    }
                }
                return $filteredRow;
            })->toArray();

            if ($format === 'excel') {
                $filename = 'rejected-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows, $exportHeadings);
                
                Log::info('Rejected Excel export completed', ['filename' => $filename, 'rows' => count($rows)]);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
            }

            // PDF export
            $pdfData = $rows;
            $pdf = Pdf::loadView('exports.rejected_registrations_pdf', compact('pdfData'));
            $filename = 'rejected-registrations-' . now()->format('Ymd-His') . '.pdf';
            
            Log::info('Rejected PDF export completed', ['filename' => $filename, 'rows' => count($rows)]);
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Rejected registration export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get only completed bookings
     */
    public function completedOnlyList(Request $request)
    {
        $statuses = ['completed'];
        $familyQuery = FamilyBooking::whereIn('status', $statuses);
        $groupQuery = GroupBooking::whereIn('status', $statuses);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $arr['display_id'] = 'F-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $arr['display_id'] = 'G-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        return response()->json(['data' => $merged]);
    }

    /**
     * API: Get only check-out bookings
     */
    public function checkoutList(Request $request)
    {
        $statuses = ['check-out'];
        $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
        $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

        $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $arr['display_id'] = 'F-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $arr['display_id'] = 'G-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        $perPage = (int) $request->get('per_page', 25);
        $page = max(1, (int) $request->get('page', 1));
        
        $total = $merged->count();
        $sliced = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $sliced,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / max(1, $perPage)) ?: 1,
            ]
        ]);
    }

    /**
     * Export check-out registrations to Excel or PDF
     * GET /api/registration/checkout-export?format=excel|pdf
     */
    public function checkoutExport(Request $request)
    {
        try {
            $format = strtolower($request->get('format', 'excel'));
            
            Log::info('Checkout registration export started', [
                'format' => $format
            ]);
            
            // Get check-out bookings data
            $statuses = ['check-out'];
            $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
            $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

            // Add search filters
            $search = $request->get('search');
            if ($search) {
                $familyQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
                $groupQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            }

            $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'family';
                $arr['booking_id'] = 'F-' . ($b->id + 100);
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';
                return $arr;
            })->values()->toBase();

            $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'group';
                $arr['booking_id'] = 'G-' . ($b->id + 100);
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';
                return $arr;
            })->values()->toBase();

            $data = $families->merge($groups);

            $visibleColumnsKeys = $request->get('visible_columns');
            $allColumnsMap = [
                'type' => 'Type',
                'booking_id' => 'Booking ID',
                'name' => 'Name',
                'father_name' => 'Father Name',
                'phone' => 'Phone',
                'aadhar_number' => 'Aadhar Number',
                'age' => 'Age',
                'mid' => 'MID',                
                'city' => 'City',
                'state' => 'State',
                'aanchal' => 'Aanchal',
                'travel_type' => 'Travel Type',
                'check_in_date' => 'Check-in Date',
                'check_out_date' => 'Check-out Date',
                'total_persons' => 'Total Persons'
            ];

            $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
            foreach ($dynamicFields as $df) {
                $allColumnsMap['extra_' . $df->name] = $df->label;
            }

            if (empty($visibleColumnsKeys) || !is_array($visibleColumnsKeys)) {
                $visibleColumnsKeys = array_keys($allColumnsMap);
            }

            $exportHeadings = [];
            foreach ($visibleColumnsKeys as $key) {
                if (isset($allColumnsMap[$key])) {
                    $exportHeadings[] = $allColumnsMap[$key];
                }
            }

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row) use ($visibleColumnsKeys, $dynamicFields) {
                $fullRow = [
                    'type' => $row['type'] ?? '',
                    'booking_id' => $row['booking_id'] ?? '',
                    'name' => $row['name'] ?? '',
                    'father_name' => $row['father_name'] ?? '',
                    'phone' => $row['phone'] ?? '',
                    'aadhar_number' => $row['aadhar_number'] ?? '',
                    'age' => $row['age'] ?? '',
                    'mid' => $row['mid'] ?? '',                    
                    'city' => $row['city'] ?? '',
                    'state' => $row['state'] ?? '',
                    'aanchal' => $row['aanchal'] ?? '',
                    'travel_type' => $row['travel_type'] ?? '',
                    'check_in_date' => $row['check_in_date'] ?? '',
                    'check_out_date' => $row['check_out_date'] ?? '',
                    'total_persons' => $row['total_persons'] ?? ''
                ];
                
                $extraData = $row['extra_fields'] ?? [];
                if (is_string($extraData)) {
                    $extraData = json_decode($extraData, true) ?? [];
                }
                foreach ($dynamicFields as $df) {
                    $fullRow['extra_' . $df->name] = $extraData[$df->name] ?? '';
                }
                
                $filteredRow = [];
                foreach ($visibleColumnsKeys as $key) {
                    if (isset($fullRow[$key])) {
                        $filteredRow[$key] = $fullRow[$key];
                    }
                }
                return $filteredRow;
            })->toArray();

            if ($format === 'excel') {
                $filename = 'checkout-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows, $exportHeadings);
                
                Log::info('Checkout Excel export completed', ['filename' => $filename, 'rows' => count($rows)]);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
            }

            // PDF export
            $pdfData = $rows;
            $pdf = Pdf::loadView('exports.checkout_registrations_pdf', compact('pdfData'));
            $filename = 'checkout-registrations-' . now()->format('Ymd-His') . '.pdf';
            
            Log::info('Checkout PDF export completed', ['filename' => $filename, 'rows' => count($rows)]);
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Checkout registration export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get only rejected bookings
     */
    public function rejectedList(Request $request)
    {
        $statuses = ['rejected'];
        $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);
        $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->whereIn('status', $statuses);

        $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

        $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'family';
            $arr['display_id'] = 'F-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            return $arr;
        })->values()->toBase();

        $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
            $arr = $b->toArray();
            $arr['type'] = 'group';
            $arr['display_id'] = 'G-' . ($b->id + 100);
            $arr['total_persons'] = $b->total_persons ?? ($b->total_members ?? null);
            $arr['city'] = $b->cityName?->city_name ?? ($b->city ?? '');
            $arr['state'] = $b->stateName?->state_name ?? ($b->state ?? '');
            $arr['aanchal'] = $b->aanchalName?->name ?? ($b->aanchal ?? '');
            return $arr;
        })->values()->toBase();

        $merged = $families->merge($groups)
            ->sortByDesc(function($item){
                return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
            })->values();

        $perPage = (int) $request->get('per_page', 25);
        $page = max(1, (int) $request->get('page', 1));
        
        $total = $merged->count();
        $sliced = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $sliced,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / max(1, $perPage)) ?: 1,
            ]
        ]);
    }

    /**
     * Check out a booking (family or group)
     */
    public function checkout(Request $request, $type, $id)
{
    if ($type === 'family') {
        $booking = FamilyBooking::findOrFail($id);
    } elseif ($type === 'group') {
        $booking = GroupBooking::findOrFail($id);
    } else {
        return response()->json(['error' => 'Invalid booking type'], 400);
    }

    // Update status to 'check-out'
    $booking->status = 'check-out';
    $booking->save();

    // Delete related entries in booked_rooms table
    // Ensure the correct booking_id is used
    $bookingId = $booking->booking_id ?? $id;
    DB::table('booked_rooms')->where('booking_id', $bookingId)->delete();

    return response()->json(['success' => true, 'message' => 'Booking checked out successfully.']);
}

    /**
     * Automatically check out bookings based on predefined time slots
     */

    public function allExport(Request $request)
    {
        try {
            $format = strtolower($request->get('format', 'excel'));
            Log::info('All registration export started', ['format' => $format]);
            
            $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName']);
            $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName']);

            $this->applyDynamicFilters($familyQuery, $groupQuery, $request);

            $families = $familyQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'family';
                $display_id = !empty($b->booking_id) ? $b->booking_id : 'F-' . ($b->id + 100);
                $arr['booking_id'] = $display_id;
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';

                $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
                if ($rooms->isNotEmpty()) {
                    $grouped = [];
                    foreach($rooms as $rm) {
                        $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                        $grouped[$hotelName][] = $rm->room_number;
                    }
                    $allotmentParts = [];
                    foreach($grouped as $hName => $rNums) {
                        $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                    }
                    $arr['allotment_info'] = implode(' | ', $allotmentParts);
                } else {
                    $arr['allotment_info'] = 'Not Allotted';
                }
                
                return $arr;
            })->values()->toBase();

            $groups = $groupQuery->orderByDesc('id')->get()->map(function($b) {
                $arr = $b->toArray();
                $arr['type'] = 'group';
                $display_id = !empty($b->booking_id) ? $b->booking_id : 'G-' . ($b->id + 100);
                $arr['booking_id'] = $display_id;
                $arr['city'] = $b->cityName->city_name ?? 'N/A';
                $arr['state'] = $b->stateName->state_name ?? 'N/A';
                $arr['aanchal'] = $b->aanchalName->name ?? 'N/A';

                $rooms = \App\Models\BookedRoom::with('hotel')->where('booking_id', $display_id)->orWhere('booking_id', $b->id)->get();
                if ($rooms->isNotEmpty()) {
                    $grouped = [];
                    foreach($rooms as $rm) {
                        $hotelName = $rm->hotel?->hotel_name ?? 'Unknown Hotel';
                        $grouped[$hotelName][] = $rm->room_number;
                    }
                    $allotmentParts = [];
                    foreach($grouped as $hName => $rNums) {
                        $allotmentParts[] = $hName . ' (' . implode(', ', $rNums) . ')';
                    }
                    $arr['allotment_info'] = implode(' | ', $allotmentParts);
                } else {
                    $arr['allotment_info'] = 'Not Allotted';
                }
                
                return $arr;
            })->values()->toBase();

            $merged = $families->merge($groups)
                ->sortByDesc(function($item){
                    return ($item['type'] === 'family' ? (1000 + (int)$item['id']) : (2000 + (int)$item['id']));
                })->values();

            $visibleColumnsKeys = $request->get('visible_columns');
            $allColumnsMap = [
                'type' => 'Type',
                'booking_id' => 'Booking ID',
                'status' => 'Status',
                'name' => 'Name',
                'father_name' => 'Father Name',
                'phone' => 'Phone',
                'aadhar_number' => 'Aadhar Number',
                'age' => 'Age',
                'mid' => 'MID',                
                'city' => 'City',
                'state' => 'State',
                'aanchal' => 'Aanchal',
                'travel_type' => 'Travel Type',
                'check_in_date' => 'Check-in Date',
                'check_in_time' => 'Check-in Time',
                'check_out_date' => 'Check-out Date',
                'check_out_time' => 'Check-out Time',
                'total_persons' => 'Total Persons',
                'allotment_info' => 'Allotment Info'
            ];

            $dynamicFields = \App\Models\DynamicField::where('status', true)->get();
            foreach ($dynamicFields as $df) {
                $allColumnsMap['extra_' . $df->name] = $df->label;
            }

            if (empty($visibleColumnsKeys) || !is_array($visibleColumnsKeys)) {
                $visibleColumnsKeys = array_keys($allColumnsMap);
            }

            $exportHeadings = [];
            foreach ($visibleColumnsKeys as $key) {
                if (isset($allColumnsMap[$key])) {
                    $exportHeadings[] = $allColumnsMap[$key];
                }
            }

            $rows = $merged->map(function($row) use ($visibleColumnsKeys, $dynamicFields) {
                $ensureUtf8 = function($value) {
                    if (!is_string($value)) return $value;
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                    }
                    return $value;
                };
                
                $fullRow = [
                    'type' => $ensureUtf8($row['type'] ?? ''),
                    'booking_id' => $ensureUtf8($row['booking_id'] ?? ''),
                    'status' => $ensureUtf8(strtoupper($row['status'] ?? 'PENDING')),
                    'name' => $ensureUtf8($row['name'] ?? ''),
                    'father_name' => $ensureUtf8($row['father_name'] ?? ''),
                    'phone' => $ensureUtf8($row['phone'] ?? ''),
                    'aadhar_number' => $ensureUtf8($row['aadhar_number'] ?? ''),
                    'age' => $ensureUtf8($row['age'] ?? ''),
                    'mid' => $ensureUtf8($row['mid'] ?? ''),                
                    'city' => $ensureUtf8($row['city'] ?? ''),
                    'state' => $ensureUtf8($row['state'] ?? ''),
                    'aanchal' => $ensureUtf8($row['aanchal'] ?? ''),
                    'travel_type' => $ensureUtf8($row['travel_type'] ?? ''),
                    'check_in_date' => $ensureUtf8($row['check_in_date'] ?? ''),
                    'check_in_time' => $ensureUtf8($row['check_in_time'] ?? ''),
                    'check_out_date' => $ensureUtf8($row['check_out_date'] ?? ''),
                    'check_out_time' => $ensureUtf8($row['check_out_time'] ?? ''),
                    'total_persons' => $ensureUtf8($row['total_persons'] ?? ''),
                    'allotment_info' => $ensureUtf8($row['allotment_info'] ?? '')
                ];

                $extraData = $row['extra_fields'] ?? [];
                if (is_string($extraData)) {
                    $extraData = json_decode($extraData, true) ?? [];
                }
                foreach ($dynamicFields as $df) {
                    $fullRow['extra_' . $df->name] = $ensureUtf8($extraData[$df->name] ?? '');
                }

                $filteredRow = [];
                foreach ($visibleColumnsKeys as $key) {
                    if (isset($fullRow[$key])) {
                        $filteredRow[$key] = $fullRow[$key];
                    }
                }
                return $filteredRow;
            })->toArray();

            if ($format === 'excel') {
                $filename = 'all-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows, $exportHeadings);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
            }

            try {
                $pdfData = $rows;
                $htmlTable = $this->generateStyledTableHtml($pdfData);
                $imagePath = $this->convertHtmlTableToImage($htmlTable);
                $pdf = Pdf::loadView('exports.registrations_pdf_image', compact('imagePath'));
                $filename = 'all-registrations-' . now()->format('Ymd-His') . '.pdf';
                return $pdf->download($filename);
            } catch (\Exception $imageError) {
                $pdfData = $rows;
                $pdf = Pdf::loadView('exports.registrations_pdf', compact('pdfData'));
                $pdf->setOptions([
                    'defaultFont' => 'dejavusans',
                    'dpi' => 96,
                    'enable_font_subsetting' => false,
                    'enable_css_float' => true,
                    'enable_php' => false,
                    'enable_remote' => false,
                ]);
                $filename = 'all-registrations-' . now()->format('Ymd-His') . '.pdf';
                return $pdf->download($filename);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate styled HTML table from registration data
     */
    private function generateStyledTableHtml(array $pdfData): string
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: Arial, sans-serif; }
        body { margin: 20px; background: white; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; }
        .header p { font-size: 12px; margin: 5px 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 11px;
        }
        tr:nth-child(even) { background-color: #f5f5f5; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pending Registrations Report</h1>
        <p>Generated on: ' . now()->format('d/m/Y H:i:s') . '</p>
        <p>Total Records: ' . count($pdfData) . '</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 7%;">Type</th>
                <th style="width: 10%;">Booking ID</th>
                <th style="width: 12%;">Name</th>
                <th style="width: 10%;">Phone</th>
                <th style="width: 10%;">City</th>
                <th style="width: 10%;">State</th>
                <th style="width: 10%;">Aanchal</th>
                <th style="width: 6%;">Check-in Date</th>
                <th style="width: 5%;">Check-in Time</th>
                <th style="width: 6%;">Check-out Date</th>
                <th style="width: 5%;">Check-out Time</th>
                <th style="width: 8%;">Total</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($pdfData as $row) {
            $html .= '<tr>
                <td>' . htmlspecialchars($row['type'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['booking_id'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['city'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['state'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['aanchal'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['check_in_date'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['check_in_time'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['check_out_date'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['check_out_time'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td class="text-center">' . htmlspecialchars($row['total_persons'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
    </table>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Convert HTML table to PNG image using Browsershot
     */
    private function convertHtmlTableToImage(string $html): string
    {
        try {
            $tempFileName = 'temp_table_' . time() . '.png';
            $tempPath = storage_path('app/exports/' . $tempFileName);
            
            // Ensure directory exists
            if (!is_dir(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }
            
            // Try to find Chrome/Chromium installation
            $chromePath = $this->findChromePath();
            
            if (!$chromePath) {
                throw new \Exception('Chrome/Chromium browser not found. Please install it or use a system with Chrome/Chromium installed.');
            }
            
            Log::info('Using Chrome path: ' . $chromePath);
            
            // Use Browsershot to convert HTML to PNG
            Browsershot::html($html)
                ->setChromePath($chromePath)
                ->windowSize(1600, 900)
                ->fullPage()
                ->save($tempPath);
            
            Log::info('Table converted to image', ['file' => $tempPath]);
            
            return $tempPath;
        } catch (\Exception $e) {
            Log::error('Failed to convert HTML to image', ['error' => $e->getMessage()]);
            throw new \Exception('Could not generate table image: ' . $e->getMessage());
        }
    }
    
    /**
     * Find Chrome/Chromium executable path
     */
    private function findChromePath(): ?string
    {
        $possiblePaths = [
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Chromium\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Chromium\\Application\\chrome.exe',
            '/usr/bin/chromium-browser',
            '/usr/bin/google-chrome',
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) || is_executable($path)) {
                return $path;
            }
        }
        
        return null;
    }

}
