<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupBooking;
use App\Models\GroupMember; 
use App\Models\HotelDetails;  
use App\Models\BookedRoom;
use Illuminate\Support\Facades\DB;
use App\Exports\GroupBookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Models\Aanchal;
use App\Exports\GroupMemberExport;
use Illuminate\Support\Facades\Log;



class GroupBookingController extends Controller
{
    // Show all bookings form 
    public function index()
    {
        return view('group-booking');
    }
    public function getMembers($id)
    {
        $members = \App\Models\GroupMember::where('group_booking_id', $id)->get();
        return response()->json($members);
    }
    



public function create(Request $request)
{
    $query = GroupBooking::query();

    // Apply search filters
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
        $city = \App\Models\City::where('city_name', 'like', '%' . $request->city . '%')->first();
        if ($city) {
            $query->where('city', $city->city_id);
        } else {
            $query->where('city', 0); // No match fallback  
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

    // ✅ New: Filter by booking_id (adjusted by -100)
    if ($request->filled('booking_id')) {
        $adjustedId = (int)$request->booking_id - 100;
        $query->where('id', $adjustedId);
    }

    // Paginate and transform to attach room data
    $bookings = $query->orderByDesc('id')->paginate(7);

    $bookings->getCollection()->transform(function ($booking) {
        $rooms = BookedRoom::where('booking_id', $booking->id)
                           ->where('booking_type', 'family')
                           ->get();

        $booking->rooms_allotted = $rooms->isNotEmpty();
        $booking->hotel_name = optional($rooms->first()?->hotel)->hotel_name ?? null;
        $booking->room_numbers = $rooms->pluck('room_number')->toArray();

        $city = \App\Models\City::find($booking->city);
        $booking->city_name = $city ? $city->city_name : 'N/A';

        $aanchal = \App\Models\Aanchal::find($booking->aanchal);
        $booking->aanchal_name = $aanchal ? $aanchal->name : 'N/A';

        return $booking;
    });

    $aanchals = Aanchal::orderBy('display_order')->get();

    return view('create', compact('bookings', 'aanchals'));
}


public function thankyou($booking_id = null)
{
    // Default null
    $booking = null;

    // ✅ Check booking_id exists
    if ($booking_id) {
        // Example: booking_id is like "G-152"
        $booking = \App\Models\GroupBooking::where('booking_id', $booking_id)->first();
    }

    // ✅ Return view with both variables
    return view('thankyou', [
        'booking' => $booking,
        'booking_id' => $booking_id,
    ]);
}





public function checkAadhar(Request $request)
{
    $aadhar = $request->aadhar_number;

    $exists = DB::table('family_booking')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('family_members')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('group_bookings')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('group_members')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('forms')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists();

    return response()->json(['exists' => $exists]);
}



public function exportGroupWithMembers()
{
    return Excel::download(new GroupMemberExport, 'group-members-full.xlsx', ExcelFormat::XLSX);
}



   // Store Data
// Replace your current store() with this corrected version:
public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'relationship_type' => 'nullable|string|in:Son of,Daughter of,Wife of',
        'aadhar_number' => 'nullable|string|max:15',
        'mid' => 'nullable|string|max:15',
        'phone' => 'required|string|max:15',
        'city' => 'required|string',
        'state' => 'required|string',
        'aanchal' => 'required|string',
        'travel_type' => 'required|string',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date',
        'check_in_time' => 'required',
        'check_out_time' => 'required',
        'total_members' => 'required|integer|min:1|max:3000',
        'total_male' => 'required|numeric|min:0',
        'total_female' => 'required|numeric|min:0',
        'child_count' => 'required|numeric|min:0',
        'sixty_plus_members' => 'nullable|numeric|min:0',
        'sixty_plus_male' => 'nullable|numeric|min:0',
        'sixty_plus_female' => 'nullable|numeric|min:0',
        'members' => 'nullable|array',
        'members.*.name' => 'nullable|string|max:255',
        'members.*.mobile_number' => 'nullable|string|max:15',
        'remark' => 'nullable|string|max:1000',
    ]);

    // Convert numeric fields to integers
    $validatedData['total_members'] = (int)$validatedData['total_members'];
    $validatedData['total_male'] = (int)$validatedData['total_male'];
    $validatedData['total_female'] = (int)$validatedData['total_female'];
    $validatedData['child_count'] = (int)$validatedData['child_count'];
    $validatedData['sixty_plus_members'] = isset($validatedData['sixty_plus_members']) ? (int)$validatedData['sixty_plus_members'] : 0;
    $validatedData['sixty_plus_male'] = isset($validatedData['sixty_plus_male']) ? (int)$validatedData['sixty_plus_male'] : 0;
    $validatedData['sixty_plus_female'] = isset($validatedData['sixty_plus_female']) ? (int)$validatedData['sixty_plus_female'] : 0;

    $totalPersons = $validatedData['total_members'] + 1;

    DB::beginTransaction();

    try {
        // create booking
        $groupBooking = GroupBooking::create([
            'name' => $validatedData['name'],
            'father_name' => $validatedData['father_name'],
            'relationship_type' => $validatedData['relationship_type'] ?? null,
            'phone' => $validatedData['phone'],
            'aadhar_number' => $validatedData['aadhar_number'] ?? null,
            'mid' => $validatedData['mid'] ?? null,
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'aanchal' => $validatedData['aanchal'],
            'travel_type' => $validatedData['travel_type'],
            'check_in_date' => $validatedData['check_in_date'],
            'check_out_date' => $validatedData['check_out_date'],
            'check_in_time' => $validatedData['check_in_time'],
            'check_out_time' => $validatedData['check_out_time'],
            'total_members' => $validatedData['total_members'],
            'total_male' => $validatedData['total_male'],
            'total_female' => $validatedData['total_female'],
            'child_count' => $validatedData['child_count'],
            'sixty_plus_members' => $validatedData['sixty_plus_members'] ?? 0,
            'sixty_plus_male' => $validatedData['sixty_plus_male'] ?? 0,
            'sixty_plus_female' => $validatedData['sixty_plus_female'] ?? 0,
            'total_persons' => $totalPersons,
            'remark' => $validatedData['remark'] ?? null,
            // booking_id will be set after we get the id
        ]);

        // generate and save booking_id
        $generatedBookingId = 'G-' . ($groupBooking->id + 100);
        $groupBooking->booking_id = $generatedBookingId;
        $groupBooking->save();

        // save members
        if (!empty($validatedData['members'])) {
            foreach ($validatedData['members'] as $member) {
                // Skip empty member entries
                if (empty($member['name']) && empty($member['mobile_number'])) {
                    continue;
                }
                
                GroupMember::create([
                    'group_booking_id' => $groupBooking->id,
                    'name' => $member['name'] ?? null,
                    'mobile_number' => $member['mobile_number'] ?? null,
                ]);
            }
        }

        DB::commit();

        // prepare SMS and redirect to thankyou
        $customBookingId = 100 + $groupBooking->id;
        $date = now()->format('d-m-Y');

        $message = "JJ, JGR Booking ID: .$customBookingId Date .$date Your registration has been successfully completed. SABSJS";

        // use the defined sendSms() method
        $this->sendSms($request->phone, $message);

        return redirect()->route('thankyou', ['booking_id' => $groupBooking->booking_id])
                 ->with('success', 'Group booking saved successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Group booking store error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        return back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}




private function sendSms($number, $message, $dlttempid = 1007932841660700100)
{
    // Optional PEID and other hardcoded values
    $user     = "JainSangh";
    $password = "Jain@12";
    $senderid = "ABSJHO";
    $channel  = "trans";
    $dcs      = "0";
    $flashsms = "0";
    $route    = "4";
    $peid     = "1001071123690830532";

    // Build parameter array
    $params = [
        'user'          => $user,
        'password'      => $password,
        'senderid'      => $senderid,
        'channel'       => $channel,
        'DCS'           => $dcs,
        'flashsms'      => $flashsms,
        'number'        => $number,
        'text'          => $message,
        'route'         => $route,
        'PEID'          => $peid,
        'DLTTemplateId' => $dlttempid, // optional or null
    ];

    $query = http_build_query($params);
    $url = "http://www.bulksms.saakshisoftware.in/api/mt/SendSMS?" . $query; 
    // Initialize CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


    // Show edit form
  public function edit($id)
{
    $booking = GroupBooking::findOrFail($id);
    return view('edit', compact('booking'));
}

public function update(Request $request, $id)
 {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'aadhar_number' => 'required|string|max:15',
        'travel_type' => 'required|string|max:50',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date',
       'check_in_time' => 'required|date_format:H:i:s',
  'check_out_time' => 'required|date_format:H:i:s',

        'total_members' => 'required|integer|min:0',
        'total_male' => 'required|integer|min:0',
        'total_female' => 'required|integer|min:0',
        'sixty_plus_members' => 'required|in:1,0',
        'sixty_plus_male' => 'nullable|integer|min:0',
        'sixty_plus_female' => 'nullable|integer|min:0',
        'remark' => 'nullable|string|max:1000',
    ]);

    $validatedData['total_persons'] = $validatedData['total_members'] + 1;

    // If 60+ Members is No, set values to null
    if ($validatedData['sixty_plus_members'] === 'No') {
        $validatedData['sixty_plus_male'] = null;
        $validatedData['sixty_plus_female'] = null;
    }

    $booking = GroupBooking::findOrFail($id);
    $booking->update($validatedData);

   return redirect()->route('group.booking.create')->with('success', 'Booking updated successfully!');
}



    // Delete booking
   public function destroy($id)
{
    // Delete all group members associated with this booking
    \App\Models\GroupMember::where('group_booking_id', $id)->delete();

    // Delete the group booking
    \App\Models\GroupBooking::findOrFail($id)->delete();

    return back()->with('success', 'Booking and associated group members deleted successfully!');
}

    public function showBookings()
    {
        // Assuming you're fetching bookings and hotel details
        $bookings = GroupBooking::paginate(10);
    
        // For each booking, fetch hotel details
        $hotels = HotelDetails::all();
    
        return view('create', compact('bookings', 'hotels'));
    }


    public function checkout($id)
{
    // 1. Delete booked rooms for this group booking
    BookedRoom::where('booking_id', $id)
              ->where('booking_type', 'group')
              ->delete();

    // 2. Update group_booking status
    GroupBooking::where('id', $id)->update(['status' => 'checkout']);

    // ✅ 3. Update all related group_members status to 'checkout'
    \App\Models\GroupMember::where('group_booking_id', $id)
        ->update(['status' => 'checkout']);

    return redirect()->back()->with('success', 'Group booking checked out successfully.');
}



    public function export()
{
    return Excel::download(new GroupBookingsExport, 'group_bookings.xlsx', ExcelFormat::XLSX);
}

}
