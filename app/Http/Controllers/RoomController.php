<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelDetails;
use App\Models\RoomCategory;
use App\Models\Category;
use App\Models\RoomFeatures;
use App\Models\BookedRoom;
use App\Exports\RoomsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\Form;
use App\Exports\RoomsExportAll;
use Illuminate\Support\Facades\DB;


class RoomController extends Controller
{
    public function createCategory($hotel_id)
    {
        $hotel = HotelDetails::findOrFail($hotel_id);
        $categories = Category::all();
        return view('hotels.create-room-category', compact('hotel', 'categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotel_details,id',
            'category_id' => 'required|exists:category,id',
            'floor' => 'required|string|max:50',
            'beds' => 'required|integer|min:1',
            'extra_capacity' => 'nullable|integer|min:0',
            'room_numbers' => 'required|string'
        ]);

        // Parse and validate room numbers
        $roomNumbers = array_map('trim', explode(',', $request->room_numbers));
        $roomNumbers = array_filter($roomNumbers); // Remove empty values

        // Check for duplicate room numbers
        $existingRooms = RoomFeatures::where('hotel_id', $request->hotel_id)
            ->whereIn('room_number', $roomNumbers)
            ->pluck('room_number')
            ->toArray();

        if (!empty($existingRooms)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'These room numbers already exist: ' . implode(', ', $existingRooms));
        }

        DB::beginTransaction();

        try {
            // Create room category
            $roomCategory = RoomCategory::create([
                'hotel_id' => $request->hotel_id,
                'category_id' => $request->category_id,
                'floor' => $request->floor,
                'beds' => $request->beds,
                'extra_capacity' => $request->extra_capacity ?? 0,
                'total_capacity' => $request->beds + ($request->extra_capacity ?? 0),
                'room_number' => implode(',', $roomNumbers)
            ]);

            // Create room features for each room number
            foreach ($roomNumbers as $roomNumber) {
                RoomFeatures::create([
                    'hotel_id' => $request->hotel_id,
                    'room_number' => $roomNumber,
                    'category_id' => $request->category_id,
                    'ac' => false,
                    'attach_bath' => false,
                    'toilet_type' => 'indian',
                    'status' => 'active'
                ]);
            }

            DB::commit();
            return redirect()->route('hotel.show', $request->hotel_id)
                ->with('success', 'Room category created successfully with ' . count($roomNumbers) . ' rooms.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create room category: ' . $e->getMessage());
        }
    }

    public function create($hotelId)
    {
        $hotel = HotelDetails::findOrFail($hotelId);
        $categories = Category::all();
        return view('hotels.rooms', compact('hotel', 'categories'));
    }

 public function store(Request $request)
{
    $duplicateRooms = [];

    foreach ($request->rooms as $room) {
        $exists = RoomFeatures::where('hotel_id', $request->hotel_id)
            ->where('room_number', $room['room_number'])
            ->exists();

        if ($exists) {
            $duplicateRooms[] = $room['room_number'];
        }
    }

    if (!empty($duplicateRooms)) {
        $message = '❌ These room numbers already exist in this hotel: ' . implode(', ', $duplicateRooms);
        return redirect()->back()
            ->withInput()
            ->with('room_error', $message); // For SweetAlert
    }

    DB::beginTransaction();

    try {
        $roomCategory = RoomCategory::create([
            'hotel_id'       => $request->hotel_id,
            'category_id'    => $request->category,
            'floor'          => $request->floor,
            'beds'           => $request->beds,
            'extra_capacity' => $request->extra_capacity,
            'total_capacity' => $request->beds + $request->extra_capacity,
            'room_number'    => $request->room_number,
        ]);

        foreach ($request->rooms as $room) {
            RoomFeatures::create([
                'hotel_id'     => $request->hotel_id,
                'room_number'  => $room['room_number'],
                'category_id'  => $request->category,
                'ac'           => $room['ac'],
                'attach_bath'  => $room['attach_bath'],
                'toilet_type'  => $room['toilet_type'],
                'status'       => $room['status'] ?? 'active', 
            ]);
        }

        DB::commit();

        return redirect()->back()->with('success', '✅ Rooms added successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('room_error', 'Something went wrong: ' . $e->getMessage());
    }
}


public function edit($id)
{
    $roomCategory = RoomCategory::findOrFail($id);
    $categories = Category::all();
    return view('hotels.edit-room-category', compact('roomCategory', 'categories'));
}


public function update(Request $request, $id)
{
    $roomCategory = RoomCategory::findOrFail($id);

    // Validate inputs
    $validated = $request->validate([
        'hotel_id' => 'required|exists:hotel_details,id',
        'category_id'      => 'required|exists:category,id',
        'floor'            => 'required|string|max:50',
        'beds'             => 'required|integer|min:0',
        'extra_capacity'   => 'nullable|integer|min:0',
        // note: room_number is kept as existing readonly field in form; new numbers come from new_room_numbers
        'new_room_numbers' => 'nullable|string',
        'room_features'    => 'nullable|array',
    ]);

    // Parse existing room numbers (preserve as-is if present)
    $existingRaw = $roomCategory->room_number ?? '';
    $existingArr = [];
    if (strlen(trim($existingRaw)) > 0) {
        $existingArr = array_filter(array_map('trim', explode(',', $existingRaw)), function ($v) {
            return $v !== '';
        });
    }

    // Parse new room numbers provided by the user (if any)
    $newArr = [];
    if (!empty($validated['new_room_numbers'])) {
        // Normalize common separators to commas, then split and trim
        $raw = str_replace([';', '|', '/', '\\', ' '], ',', $validated['new_room_numbers']);
        $parts = array_map('trim', explode(',', $raw));
        $newArr = array_values(array_filter($parts, function ($v) {
            return $v !== '';
        }));
    }

    // Check for duplicate room numbers in this hotel (excluding current row's own rooms)
    if (!empty($newArr)) {
        $duplicates = RoomFeatures::where('hotel_id', $validated['hotel_id'])
            ->whereIn('room_number', $newArr)
            ->pluck('room_number')
            ->toArray();

        if (!empty($duplicates)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'These room numbers already exist in this hotel: ' . implode(', ', $duplicates));
        }
    }

    // Merge existing + new, keep unique (do not remove existing)
    $merged = array_values(array_unique(array_merge($existingArr, $newArr)));

    // Optional: sort numerically if all items are numeric
    $allNumeric = count($merged) > 0 && collect($merged)->every(function ($i) {
        return is_numeric($i);
    });
    if ($allNumeric) {
        sort($merged, SORT_NUMERIC);
    } else {
        sort($merged, SORT_STRING);
    }

    // Prepare final room_number string — if merged non-empty use it, otherwise keep existingRaw
    $finalRoomString = count($merged) ? implode(',', $merged) : $existingRaw;

    // Compute total_capacity (ensure extra_capacity treated as 0 if null)
    $beds = (int) $validated['beds'];
    $extra = isset($validated['extra_capacity']) ? (int) $validated['extra_capacity'] : 0;
    $totalCapacity = $beds + $extra;

    // Update model fields
    $roomCategory->hotel_id = $validated['hotel_id'];
    $roomCategory->category_id = $validated['category_id'];
    $roomCategory->floor = $validated['floor'];
    $roomCategory->beds = $beds;
    $roomCategory->extra_capacity = $validated['extra_capacity'] ?? null;
    $roomCategory->room_number = $finalRoomString;
    $roomCategory->total_capacity = $totalCapacity;

    $roomCategory->save();

    // Create RoomFeatures entries for newly added rooms (so availability page shows them)
    $roomFeaturesInput = $request->input('room_features', []);
    foreach ($newArr as $newRoom) {
        $feat = $roomFeaturesInput[$newRoom] ?? [];
        RoomFeatures::firstOrCreate(
            ['hotel_id' => $validated['hotel_id'], 'room_number' => $newRoom],
            [
                'category_id'  => $validated['category_id'],
                'ac'           => in_array($feat['ac'] ?? '', ['AC', 'Non-AC']) ? $feat['ac'] : 'Non-AC',
                'attach_bath'  => in_array($feat['attach_bath'] ?? '', ['Yes', 'No']) ? $feat['attach_bath'] : 'No',
                'toilet_type'  => in_array($feat['toilet_type'] ?? '', ['Indian', 'Western']) ? $feat['toilet_type'] : 'Indian',
                'status'       => 'active',
            ]
        );
    }

    return redirect()->route('room-category.edit', $id)->with('success', 'Room category updated successfully.');
}

public function deleteRoom(Request $request, $id)
{
    $roomCategory = RoomCategory::findOrFail($id);

    // Support comma-separated room numbers
    $toDelete = array_values(array_filter(array_map('trim', explode(',', $request->delete_room_number))));

    if (empty($toDelete)) {
        return redirect()->back()->with('error', 'Please enter at least one room number.');
    }

    $roomNumbers = array_filter(array_map('trim', explode(',', $roomCategory->room_number)));
    $filtered = array_values(array_filter($roomNumbers, function ($room) use ($toDelete) {
        return !in_array($room, $toDelete);
    }));

    $roomCategory->room_number = implode(',', $filtered);
    $roomCategory->save();

    RoomFeatures::where('hotel_id', $roomCategory->hotel_id)
        ->whereIn('room_number', $toDelete)
        ->delete();

    $msg = count($toDelete) > 1
        ? 'Rooms ' . implode(', ', $toDelete) . ' deleted successfully.'
        : 'Room ' . $toDelete[0] . ' deleted successfully.';

    return redirect()->back()->with('success', $msg);
}

public function destroy($id)
{
    $roomCategory = RoomCategory::findOrFail($id);

    // Delete all related room features
    $roomNumbers = array_filter(array_map('trim', explode(',', $roomCategory->room_number)));
    RoomFeatures::where('hotel_id', $roomCategory->hotel_id)
        ->whereIn('room_number', $roomNumbers)
        ->delete();

    $roomCategory->delete();

    return redirect()->back()->with('success', 'Room category and its rooms deleted successfully.');
}


public function editRoomFeatures($category_id, $hotel_id)
{
    $features = RoomFeatures::where('category_id', $category_id)
        ->where('hotel_id', $hotel_id)
        ->get();

    $category = Category::find($category_id);
    $hotel = HotelDetails::find($hotel_id);

    return view('hotels.edit-room-features', compact('features', 'category', 'hotel'));
}

public function updateRoomFeatures(Request $request)
{
    foreach ($request->features as $roomId => $data) {
        RoomFeatures::where('id', $roomId)->update([
            'ac' => $data['ac'],
            'attach_bath' => $data['attach_bath'],
            'toilet_type' => $data['toilet_type'],
            'status'       => $data['status'],
        ]);
    }

    return redirect()->back()->with('success', 'Room features updated successfully!');
}



    public function showAllotPage($type, $id)
    {
        $data = null;
        $members = 1; // default for VIP
    
        if ($type === 'form') {
            $data = Form::find($id);
        } elseif ($type === 'family') {
            $data = FamilyBooking::find($id);
            $members = $data->total_members;
        } elseif ($type === 'group') {
            $data = GroupBooking::find($id);
            $members = $data->total_members;
        }
    
        $rooms = RoomCategory::where('total_capacity', '>', 0)->get();
    
        return view('forms.allot-room', [
            'form' => $data,    // Rename 'data' to 'form' so the Blade view can access $form->name etc.
            'type' => $type,
            'id' => $id,
            'rooms' => $rooms,
            'members' => $members
        ]);
        
    }
    public function saveAllotRoom(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $members = (int)$request->members;
    
        $room = RoomCategory::where('room_number', $request->room_number)->first();
        $available = $room->total_capacity - BookedRoom::where('room_number', $room->room_number)->sum('total_capacity');
    
        if ($members <= $available) {
            BookedRoom::create([
                'hotel_id' => $room->hotel_id,
                'room_number' => $room->room_number,
                'total_capacity' => $members,
                'booking_id' => $id,
                'booking_type' => $type,
            ]);
    
            return redirect()->route('forms.index')->with('success', 'Room Allotted Successfully');
        }
    
        return back()->withErrors(['Room does not have enough capacity']);
    }
    public function getRoomDetails($id)
    {
        $room = RoomCategory::find($id);
    
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
    
        return response()->json([
            'category_name' => $room->category->category_name,
            'floor' => $room->floor,
            'beds' => $room->beds,
            'extra_capacity' => $room->extra_capacity,
            'total_capacity' => $room->total_capacity,
            'toilet_type' => $room->toilet_type,
            'is_ac' => $room->is_ac ? 'Yes' : 'No',
            'attached_bathroom' => $room->attached_bathroom ? 'Yes' : 'No',
        ]);
    }

    public function export($hotel_id)
{
    $hotel = \App\Models\HotelDetails::findOrFail($hotel_id);
    return Excel::download(new RoomsExport($hotel_id), 'Available_Rooms.xlsx',ExcelFormat::XLSX);
}

public function exportAll()
{
    return Excel::download(new RoomsExportAll(), 'Available_Rooms_All_Hotels.xlsx',ExcelFormat::XLSX);
}

}
