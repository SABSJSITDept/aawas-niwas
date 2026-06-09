<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelDetails;
use App\Models\RoomFeature;
use App\Models\Booking;
use App\Models\RoomFeatures;

class BookingController extends Controller
{
    public function create(HotelDetails $hotel)
    {
        $rooms = $hotel->roomFeatures()->where('is_booked', false)->get();
        return view('booking.create', compact('hotel', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:room_features,id',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'beds_required' => 'required|integer|min:1',
        ]);

        $room = RoomFeatures::find($request->room_id);

        if ($room->is_booked) {
            return back()->with('error', 'Room already booked!');
        }

        // Create a booking
        Booking::create([
            'hotel_id' => $request->hotel_id,
            'room_id' => $room->id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'beds_required' => $request->beds_required,
        ]);

        // Update room status
        $room->is_booked = true;
        $room->save();

        return redirect()->route('booking.create', $room->hotel_id)->with('success', 'Room booked successfully!');
    }
}
