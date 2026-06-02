<?php

use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\BookedRoom;
use App\Models\City;
use App\Models\State;
use App\Models\Room;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
use Illuminate\Support\Str;

// 1. Fix Database Schema dynamically if needed
if (!Schema::hasColumn('booked_rooms', 'booking_type')) {
    echo "Adding missing 'booking_type' column to booked_rooms table...\n";
    Schema::table('booked_rooms', function (Blueprint $table) {
        $table->string('booking_type')->nullable()->after('room_number');
    });
}

// 2. Fetch required reference data
$city = City::first();
$state = State::first();
$rooms = Room::where('status', 'available')->take(10)->get();

if (!$city || !$state) {
    die("Please ensure you have at least one City and State in the database.\n");
}

if ($rooms->isEmpty()) {
    die("Please ensure you have at least one available Room in the database.\n");
}

echo "Generating Dummy Data for 15 Days...\n";

// Clear previous test data to prevent overlapping (Optional, but good for clean testing)
// FamilyBooking::where('remark', 'Testing dummy entry')->delete();
// GroupBooking::where('remark', 'Testing dummy entry')->delete();
// BookedRoom::where('booking_type', 'family')->orWhere('booking_type', 'group')->delete();

$startDate = Carbon::now();

for ($i = 0; $i < 15; $i++) {
    $checkInDate = $startDate->copy()->addDays($i)->format('Y-m-d');
    $checkOutDate = $startDate->copy()->addDays($i + rand(1, 3))->format('Y-m-d');
    $checkInTime = '10:00';
    $checkOutTime = '12:00';

    // ==========================================
    // Generate Family Booking
    // ==========================================
    $familyBooking = new FamilyBooking();
    $familyBooking->name = "Family Head " . $i;
    $familyBooking->father_name = "Father " . $i;
    $familyBooking->age = rand(25, 60);
    $familyBooking->gender = "Male";
    $familyBooking->phone = "90000030" . str_pad($i, 2, '0', STR_PAD_LEFT);
    $familyBooking->ms_name = "MS " . $i;
    $familyBooking->aadhar_number = "12345678" . rand(1000, 9999);
    $familyBooking->mid = "MID" . $i;
    $familyBooking->city = $city->id;
    $familyBooking->state = $state->id;
    $familyBooking->aanchal = 3; 
    $familyBooking->travel_type = "Train";
    $familyBooking->check_in_date = $checkInDate;
    $familyBooking->check_out_date = $checkOutDate;
    $familyBooking->check_in_time = $checkInTime;
    $familyBooking->check_out_time = $checkOutTime;
    
    // Fix: family_coming must be 0 or 1, not "yes"
    $familyBooking->family_coming = 1; 
    
    $familyBooking->no_of_people = 4;
    $familyBooking->total_persons = 4;
    $familyBooking->no_of_children = 1;
    $familyBooking->total_male = 2;
    $familyBooking->total_female = 2;
    $familyBooking->sixty_plus_members = 1;
    $familyBooking->sixty_plus_male = 1;
    $familyBooking->sixty_plus_female = 0;
    $familyBooking->is_veer_parivar = "no";
    
    // Generate booking ID
    $familyBookingIdStr = 'FAM' . time() . rand(1000, 9999);
    $familyBooking->booking_id = $familyBookingIdStr;
    $familyBooking->remark = "Testing dummy entry";
    $familyBooking->status = 'pending';
    
    $familyBooking->save();

    // Assign a room for Family
    if ($rooms->count() > 0) {
        $room = $rooms->random();
        BookedRoom::create([
            'booking_id' => $familyBookingIdStr,
            'hotel_id' => $room->hotel_id,
            'room_number' => $room->room_number,
            'booking_type' => 'family',
            'total_capacity' => $room->capacity ?? 2,
            'mobile_number' => $familyBooking->phone,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
        ]);
    }

    // ==========================================
    // Generate Group Booking
    // ==========================================
    $groupBooking = new GroupBooking();
    $groupBooking->name = "Group Leader " . $i;
    $groupBooking->father_name = "Father Group " . $i;
    $groupBooking->age = rand(30, 50);
    $groupBooking->gender = "Male";
    $groupBooking->phone = "90000040" . str_pad($i, 2, '0', STR_PAD_LEFT);
    $groupBooking->ms_name = "MS Grp " . $i;
    $groupBooking->aadhar_number = "87654321" . rand(1000, 9999);
    $groupBooking->mid = "MIDG" . $i;
    $groupBooking->city = $city->id;
    $groupBooking->state = $state->id;
    $groupBooking->aanchal = 3;
    $groupBooking->travel_type = "Bus";
    $groupBooking->check_in_date = $checkInDate;
    $groupBooking->check_out_date = $checkOutDate;
    $groupBooking->check_in_time = $checkInTime;
    $groupBooking->check_out_time = $checkOutTime;
    
    $groupBooking->total_members = 15;
    $groupBooking->total_persons = 15;
    $groupBooking->total_male = 10;
    $groupBooking->total_female = 5;
    $groupBooking->no_of_children = 0;
    $groupBooking->sixty_plus_members = 2;
    $groupBooking->sixty_plus_male = 1;
    $groupBooking->sixty_plus_female = 1;
    $groupBooking->is_veer_parivar = "no";
    
    // Generate booking ID
    $groupBookingIdStr = 'GRP' . time() . rand(1000, 9999);
    $groupBooking->booking_id = $groupBookingIdStr;
    $groupBooking->remark = "Testing dummy entry";
    $groupBooking->status = 'pending';

    $groupBooking->save();

    // Assign 3 rooms for Group
    if ($rooms->count() > 2) {
        $selectedRooms = $rooms->random(3);
        foreach ($selectedRooms as $room) {
            BookedRoom::create([
                'booking_id' => $groupBookingIdStr,
                'hotel_id' => $room->hotel_id,
                'room_number' => $room->room_number,
                'booking_type' => 'group',
                'total_capacity' => $room->capacity ?? 4,
                'mobile_number' => $groupBooking->phone,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
            ]);
        }
    }
}

echo "Successfully created dummy data for next 15 days.\n";
