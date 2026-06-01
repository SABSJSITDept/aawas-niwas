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

    /**
     * Return merged list of pending bookings from family_booking and group_bookings
     * Returns JSON with manual pagination (so we can merge two different table structures)
     */
  public function index(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $page = max(1, (int) $request->get('page', 1));

    // Common filters
    $search = $request->get('search');
    $aadhar = $request->get('aadhar_number');
    $city = $request->get('city');
    $aanchal = $request->get('aanchal');
    $travel_type = $request->get('travel_type');
    $check_in_date = $request->get('check_in_date');
    $booking_id = $request->get('booking_id');

    // Base queries (only pending) - with relationships for city/state/aanchal names
    $familyQuery = FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');
    $groupQuery = GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');

    // Apply filters to both queries
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

    if ($aadhar) {
        $familyQuery->where('aadhar_number', 'like', "%$aadhar%");
        $groupQuery->where('aadhar_number', 'like', "%$aadhar%");
    }

    if ($city) {
        $familyQuery->where('city', 'like', "%$city%");
        $groupQuery->where('city', 'like', "%$city%");
    }

    if ($aanchal) {
        $familyQuery->where('aanchal', $aanchal);
        $groupQuery->where('aanchal', $aanchal);
    }

    if ($travel_type) {
        $familyQuery->where('travel_type', $travel_type);
        $groupQuery->where('travel_type', $travel_type);
    }

    if ($check_in_date) {
        $familyQuery->where('check_in_date', $check_in_date);
        $groupQuery->where('check_in_date', $check_in_date);
    }

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
        // Allowed/editable fields for family (do NOT include booking_id, booking_type, status, created_at, updated_at)
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
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'aadhar_number' => "required|string|size:12|unique:family_booking,aadhar_number,{$id}",
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

        // Only pick allowed fields — prevents accidental updates to protected columns
        $data = array_intersect_key($request->all(), array_flip($allowed));

        // Optional: if total_persons missing but total_male/total_female present, you might compute it,
        // but we'll trust the frontend value. If you want auto-calculation uncomment below:
        // if (!isset($data['total_persons']) && (isset($data['total_male']) || isset($data['total_female']))) {
        //     $data['total_persons'] = ($data['total_male'] ?? $booking->total_male ?? 0) + ($data['total_female'] ?? $booking->total_female ?? 0) + 1; // if you want +1 logic
        // }

        $booking->fill($data);
        $booking->save();

        return response()->json(['success' => true, 'booking' => $booking]);
    }

    if ($type === 'group') {
        // Allowed/editable fields for group
        $allowed = [
            'name','father_name','phone','aadhar_number','mid',
            'city','state','aanchal','travel_type',
            'check_in_date','check_in_time','check_out_date','check_out_time',
            'total_persons','total_members','total_male','total_female',
            'sixty_plus_members','sixty_plus_male','sixty_plus_female',
            'status' /* note: keep status out if you want it only changeable via changeStatus(); remove if you do not want status updated here */
        ];

        // If you want status to be only changed via changeStatus(), remove 'status' from $allowed and rules below.
        // Here I will exclude 'status' from rules/allowed to match earlier UX decision:
        $allowed = array_diff($allowed, ['status']);

        $rules = [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'aadhar_number' => "required|string|size:12|unique:group_bookings,aadhar_number,{$id}",
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
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $booking = GroupBooking::findOrFail($id);

        $data = array_intersect_key($request->all(), array_flip($allowed));

        // If frontend removed total_members and uses total_persons only, ensure total_persons saved.
        // You might also want to keep total_members in sync: optionally compute total_members = total_persons - 1
        if (!isset($data['total_members']) && isset($data['total_persons'])) {
            // if your business rule is total_members = total_persons - 1 (head + members), enable this:
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

      private function getMergedDataForExport(Request $request)
    {
        // copy the same filters/merge logic from your index()
        // Return a Collection of arrays (not Eloquent models) so export is simple.

        // We'll reuse your index() logic but return full arrays
        $perPage = 1000000; // large, for export
        $page = 1;

        $search = $request->get('search');
        $aadhar = $request->get('aadhar_number');
        $city = $request->get('city');
        $aanchal = $request->get('aanchal');
        $travel_type = $request->get('travel_type');
        $check_in_date = $request->get('check_in_date');
        $booking_id = $request->get('booking_id');

        $familyQuery = \App\Models\FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');
        $groupQuery = \App\Models\GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->where('status', 'pending');

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

        if ($aadhar) {
            $familyQuery->where('aadhar_number', 'like', "%$aadhar%");
            $groupQuery->where('aadhar_number', 'like', "%$aadhar%");
        }

        if ($city) {
            $familyQuery->where('city', 'like', "%$city%");
            $groupQuery->where('city', 'like', "%$city%");
        }

        if ($aanchal) {
            $familyQuery->where('aanchal', $aanchal);
            $groupQuery->where('aanchal', $aanchal);
        }

        if ($travel_type) {
            $familyQuery->where('travel_type', $travel_type);
            $groupQuery->where('travel_type', $travel_type);
        }

        if ($check_in_date) {
            $familyQuery->where('check_in_date', $check_in_date);
            $groupQuery->where('check_in_date', $check_in_date);
        }

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

            // prepare flat rows — pick only the columns you specified
            // Ensure all strings are properly UTF-8 encoded
            $rows = $data->map(function($row){
                $ensureUtf8 = function($value) {
                    if (!is_string($value)) return $value;
                    // Check if already UTF-8, if not convert
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                    }
                    return $value;
                };
                
                return [
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
            })->toArray();

        if ($format === 'excel') {
            // Excel export using proper export class
            $filename = 'registrations-' . now()->format('Ymd-His') . '.xlsx';
            $export = new RegistrationExport($rows);
            
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

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row){
                return [
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
            })->toArray();

            if ($format === 'excel') {
                $filename = 'completed-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows);
                
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

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row){
                return [
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
            })->toArray();

            if ($format === 'excel') {
                $filename = 'rejected-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows);
                
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

            // Prepare flat rows for export - only required columns
            $rows = $data->map(function($row){
                return [
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
            })->toArray();

            if ($format === 'excel') {
                $filename = 'checkout-registrations-' . now()->format('Ymd-His') . '.xlsx';
                $export = new RegistrationExport($rows);
                
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
