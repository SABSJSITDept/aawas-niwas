<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HotelDetails;
use App\Models\RoomCategory;
use App\Models\Category;
use App\Models\BookedRoom;
use Illuminate\Support\Facades\DB;
use App\Models\RoomStatus;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HotelsExport;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Exception;





class HotelController extends Controller
{
    public function index()
    {
        $hotels = HotelDetails::all();
        return view('hotels.index', compact('hotels'));
    }

    public function create() {
        $categories = Category::pluck('category_name'); // Model se categories fetch karein
        return view('hotels.create', compact('categories'));
    }
    
   public function store(Request $request)
{
    // 1) validate incoming data properly
    $validated = $request->validate([
        'hotel_name'       => 'required|string|max:255',
        'incharge_name'    => 'required|string|max:255',
        'contact_number'   => 'required|string|max:20',
        'common_bath'      => 'required|in:Yes,No',
        'lift'             => 'required|in:Yes,No',
        'generator'        => 'required|in:Yes,No',
        'address'          => 'required|string',
        'total_rooms'      => 'required|integer|min:0',
        'google_maps_link' => 'nullable|url',
        'status'           => 'nullable|in:active,inactive', // validation rule only
    ]);

    // 2) set a sensible default for status if not provided in form
    if (empty($validated['status'])) {
        $validated['status'] = 'active'; // या 'active' जैसा चाहिए
    }

    $additionalContacts = [];
    $names = $request->input('additional_contact_name', []);
    $phones = $request->input('additional_contact_phone', []);
    foreach ($names as $index => $name) {
        if (!empty($name) && !empty($phones[$index])) {
            $additionalContacts[] = [
                'name' => $name,
                'phone' => $phones[$index]
            ];
        }
    }
    $validated['additional_contacts'] = empty($additionalContacts) ? null : $additionalContacts;

    // 3) create record using validated data
    $hotel = \App\Models\HotelDetails::create($validated);

    return redirect()->route('hotel.index')->with('success', 'Hotel and Room Categories Added Successfully!');
}

    public function show($id)
{
    $hotel = HotelDetails::with(['roomCategories', 'roomFeatures'])->findOrFail($id);
    return view('hotels.show', compact('hotel'));
}


    // Show Edit Form
public function edit($id)
{
    $hotel = HotelDetails::findOrFail($id);
    return view('hotels.edit', compact('hotel'));
}

// Update Hotel
public function update(Request $request, $id)
{
    $request->validate([
        'hotel_name' => 'required|string|max:255',
        'incharge_name' => 'required|string|max:255',
        'contact_number' => 'required|digits:10',
        'total_rooms' => 'required|integer|min:1',
        'common_bath' => 'required|in:yes,no',
        'lift' => 'required|in:yes,no',
        'generator' => 'required|in:yes,no',
        'address' => 'required|string',
        'google_maps_link' => 'nullable|url',
        'status' => 'nullable|in:active,inactive',
    ]);

    $additionalContacts = [];
    $names = $request->input('additional_contact_name', []);
    $phones = $request->input('additional_contact_phone', []);
    foreach ($names as $index => $name) {
        if (!empty($name) && !empty($phones[$index])) {
            $additionalContacts[] = [
                'name' => $name,
                'phone' => $phones[$index]
            ];
        }
    }

    $hotel = HotelDetails::findOrFail($id);
    $hotel->update([
        'hotel_name' => $request->hotel_name,
        'incharge_name' => $request->incharge_name,
        'contact_number' => $request->contact_number,
        'additional_contacts' => empty($additionalContacts) ? null : $additionalContacts,
        'total_rooms' => $request->total_rooms,
        'common_bath' => $request->common_bath,
        'lift' => $request->lift,
        'generator' => $request->generator,
        'address' => $request->address,
        'google_maps_link'  => $request->google_maps_link,
        'status' => $request->status ?? 'active',
    ]);

    return redirect()->route('hotel.index')->with('success', 'Hotel updated successfully!');
}

    // Delete Hotel
    public function destroy($id)
{
    // Begin transaction to ensure atomic delete
    DB::beginTransaction();

    try {
        $hotel = HotelDetails::findOrFail($id);

        // If you have Eloquent relationships defined, delete related entries:
        if (method_exists($hotel, 'roomCategories')) {
            $hotel->roomCategories()->delete();
        } else {
            // fallback to direct DB delete if relation not present
            DB::table('room_category')->where('hotel_id', $hotel->id)->delete();
        }

        if (method_exists($hotel, 'roomFeatures')) {
            $hotel->roomFeatures()->delete();
        } else {
            // fallback to direct DB delete if relation not present
            DB::table('room_features')->where('hotel_id', $hotel->id)->delete();
        }

        // finally delete the hotel
        $hotel->delete();

        DB::commit();

        return redirect()->route('admin.dashboard')->with('success', 'Hotel and related records deleted successfully!');
    } catch (Exception $e) {
        DB::rollBack();
        // Log the error if you want: \Log::error($e->getMessage());
        return redirect()->back()->with('error', 'Failed to delete hotel: ' . $e->getMessage());
    }
}


    public function actions($id)
{
    $hotel = HotelDetails::findOrFail($id);
    return view('hotels.actions', compact('hotel'));
}

public function toggleStatus($id)
{
    $hotel = HotelDetails::findOrFail($id);
    $hotel->status = $hotel->status === 'active' ? 'inactive' : 'active';
    $hotel->save();

    return redirect()->back()->with('success', 'Hotel status updated successfully!');
}


public function checkAvailability($id)
{
    $hotel = HotelDetails::findOrFail($id);

    // Fetch all rooms status related to the hotel
    $roomStatuses = RoomStatus::where('hotel_id', $id)->get();

    // Filter rooms with available capacity
    $availableRooms = $roomStatuses->filter(function ($room) {
        return $room->available_capacity > 0;
    });

    return view('hotels.availability', compact('hotel', 'availableRooms'));
}

public function showHotelRooms($hotel_id)
{
    $hotel = HotelDetails::findOrFail($hotel_id);

    $categories = RoomCategory::with('category')
        ->where('hotel_id', $hotel_id)
        ->get();

    return view('hotels.availability', [
        'hotel' => $hotel,
        'categories' => $categories,
    ]);
}

 public function showHotelForm()
{
    $hotels = HotelDetails::all();
    return view('select_hotel', compact('hotels'));
}

public function getHotelDetails($id)
{
    $hotel = HotelDetails::find($id);
    return response()->json($hotel);
}

public function getAllHotels()
{
    $hotels = HotelDetails::all();
    return response()->json($hotels);
}

// Excel export
public function exportHotelsExcel()
{
    return Excel::download(new HotelsExport, 'hotels.xlsx');
}

}


