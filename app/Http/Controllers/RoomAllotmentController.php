<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\HotelDetails;
use App\Models\RoomCategory;
use App\Models\BookedRoom;
use App\Models\GroupBooking;
use App\Models\FamilyBooking;
use App\Models\RoomStatus;
use App\Models\Form;
use App\Models\FamilyMember;
use App\Models\GroupMember;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class RoomAllotmentController extends Controller
{
    public function show(Request $request)
    {
        $hotels = HotelDetails::where('status', 'active')->get();
        $selectedHotelId = $request->hotel_id;
        $bookingId = $request->booking_id;
        $bookingType = $request->booking_type;
        $totalPersons = 0;
        $roomsBooked = [];

        $checkInDate = null;
        $checkOutDate = null;
        $mobileNumber = null;

        if ($bookingType === 'family') {
            $booking = FamilyBooking::find($bookingId);
            $totalPersons = $booking?->total_persons ?? 0;
            $checkInDate = $booking?->check_in_date;
            $checkOutDate = $booking?->check_out_date;
            $mobileNumber = $booking?->phone;
        } elseif ($bookingType === 'group') {
            $booking = GroupBooking::find($bookingId);
            $totalPersons = $booking?->total_persons ?? 0;
            $checkInDate = $booking?->check_in_date;
            $checkOutDate = $booking?->check_out_date;
            $mobileNumber = $booking?->phone;
        } elseif ($bookingType === 'vip') {
            $booking = Form::find($bookingId);
            $totalPersons = 1;
            $checkInDate = $booking?->check_in_date;
            $checkOutDate = $booking?->check_out_date;
            $mobileNumber = $booking?->phone;
        }

        $categories = [];
        if ($selectedHotelId) {
            $categories = RoomCategory::with('category')
                ->where('hotel_id', $selectedHotelId)
                ->get();
        }

        return view('alot_room', [
            'hotels' => $hotels,
            'categories' => $categories,
            'booking_id' => $bookingId,
            'total_persons' => $totalPersons,
            'booking_type' => $bookingType,
            'selectedHotelId' => $selectedHotelId,
            'roomsBooked' => $roomsBooked,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
            'mobileNumber' => $mobileNumber,
        ]);
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        // booking_id required (string or integer depending on your usage)
        'booking_id' => 'required',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after_or_equal:check_in_date|after_or_equal:today',
        'rooms' => 'required|array|min:1',
        'rooms.*.hotel_id' => 'required|integer',
        'rooms.*.room_number' => 'required|string',
        'rooms.*.capacity' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {

        $savedRooms = [];
        $bookingIdentifier = $request->booking_id;
        $bookingType = $request->booking_type;

        if ($bookingType === 'family') {
            $bookingModel = \App\Models\FamilyBooking::find($request->booking_id);
        } elseif ($bookingType === 'group') {
            $bookingModel = \App\Models\GroupBooking::find($request->booking_id);
        } elseif ($bookingType === 'vip') {
            $bookingModel = \App\Models\Form::find($request->booking_id);
        } else {
            // fallback: try all in order
            $bookingModel = \App\Models\FamilyBooking::find($request->booking_id)
                ?? \App\Models\GroupBooking::find($request->booking_id)
                ?? \App\Models\Form::find($request->booking_id);
        }

        // Use human-readable booking_id for both BookedRoom and SMS
        $dbBookingId = $bookingModel ? $bookingModel->id : $request->booking_id;
        $displayBookingId = ($bookingModel && isset($bookingModel->booking_id) && $bookingModel->booking_id) ? $bookingModel->booking_id : $dbBookingId;

        $mobile = $request->mobile_number ?? ($bookingModel?->phone ?? null);

        foreach ($request->rooms as $room) {
            $hotelId = $room['hotel_id'];
            $roomNum = trim($room['room_number']);
            $cap = (int) $room['capacity'];

            // Check if room is available for the requested dates
            $existingBookings = \App\Models\BookedRoom::where('hotel_id', $hotelId)
                ->where('room_number', $roomNum)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->whereDate('check_in_date', '>=', $request->check_in_date)
                          ->whereDate('check_in_date', '<=', $request->check_out_date);
                    })->orWhere(function($q) use ($request) {
                        $q->whereDate('check_out_date', '>=', $request->check_in_date)
                          ->whereDate('check_out_date', '<=', $request->check_out_date);
                    })->orWhere(function($q) use ($request) {
                        $q->whereDate('check_in_date', '<=', $request->check_in_date)
                          ->whereDate('check_out_date', '>=', $request->check_out_date);
                    });
                })
                ->sum('total_capacity');

            $roomCategory = RoomCategory::where('hotel_id', $hotelId)
                ->get()
                ->first(function ($category) use ($roomNum) {
                    return in_array($roomNum, array_map('trim', explode(',', $category->room_number)));
                });

            if (!$roomCategory || ($existingBookings + $cap) > $roomCategory->total_capacity) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => "Room {$roomNum} is not available for the selected dates."
                ], 422);
            }

            \App\Models\BookedRoom::create([
                'booking_id' => $displayBookingId, // always save displayBookingId (same as SMS)
                'hotel_id' => $hotelId,
                'room_number' => $roomNum,
                'total_capacity' => $cap,
                'mobile_number' => $mobile,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
            ]);

            $savedRooms[] = ['hotel_id' => $hotelId, 'room_number' => $roomNum];

            $status = \App\Models\RoomStatus::firstOrNew(['hotel_id' => $hotelId, 'room_number' => $roomNum]);
            $status->available_capacity = max(0, ($status->available_capacity ?? 0) - $cap);
            $status->status = $status->available_capacity <= 0 ? 'Full' : 'Partial';
            $status->save();
        }

        // mark booking as completed and members updated
        if ($bookingModel) {
            $bookingModel->status = 'completed';
            $bookingModel->save();

            if ($bookingModel instanceof \App\Models\FamilyBooking) {
                \App\Models\FamilyMember::where('family_id', $bookingModel->id)->update(['status' => 'completed']);
            } elseif ($bookingModel instanceof \App\Models\GroupBooking) {
                \App\Models\GroupMember::where('group_booking_id', $bookingModel->id)->update(['status' => 'completed']);
            }
        }

        DB::commit();

        // Build hotel-wise summary (Hotel Name: room1, room2)
        $grouped = [];
        foreach ($savedRooms as $r) {
            $grouped[$r['hotel_id']][] = $r['room_number'];
        }

        $hotelSummaryParts = [];
        $allRooms = [];
        foreach ($grouped as $hid => $roomsArr) {
            $hotel = \App\Models\HotelDetails::find($hid);
            $hotelName = $hotel?->hotel_name ?? ("Hotel-{$hid}");
            $hotelSummaryParts[] = $hotelName . ': ' . implode(', ', $roomsArr);
            $allRooms = array_merge($allRooms, $roomsArr);
        }
        $hotelSummary = implode(' | ', $hotelSummaryParts); 
        $messageForUser = "Rooms allotted! Booking ID: {$displayBookingId}. " . $hotelSummary;

        // Send SMS (if mobile present)
        if ($mobile) {
            // Prepare SMS as per registered template
            // Example: JJ, JGR Your Room No .102 Venue .SAMTA BHAWAN Helpling No 6375359089 check https://chaturmas.sadhumargi.in/booking-pdf SABSJS
            $roomNumbers = [];
            $venueNames = [];
            foreach ($savedRooms as $r) {
                $roomNumbers[] = $r['room_number'];
                $hotel = \App\Models\HotelDetails::find($r['hotel_id']);
                if ($hotel) $venueNames[] = $hotel->hotel_name;
            }
            $roomStr = implode(',', $roomNumbers);
            $venueStr = implode(',', array_unique($venueNames));
            $smsText = "JJ, JGR Room has been successfully allotted. Room No.: .$roomStr Hotel Name: .$venueStr SABSJS";
            try {
                $this->sendSms($mobile, $smsText, '1007339054014631095');
            } catch (\Throwable $e) {
                Log::error('SMS send failed: ' . $e->getMessage());
            }
        }

        // Always return JSON response
        return response()->json([
            'status' => 'success',
            'message' => $messageForUser,
            'hotel_summary' => $hotelSummary,
            'rooms' => $allRooms,
            'booking_id' => $displayBookingId,
            'redirect_url' => route('registration.list')
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Room allot error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Something went wrong while allotting rooms.'
            ], 500);
    }
}

    private function sendSms($number, $message, $dlttempid = '1007868334576726908')
    {
        $params = [
            'user' => 'JainSangh',
            'password' => 'Jain@12',
            'senderid' => 'ABSJHO',
            'channel' => 'trans',
            'DCS' => '0',
            'flashsms' => '0',
            'number' => $number,
            'text' => $message,
            'route' => '4',
            'PEID' => '1001071123690830532',
            'DLTTemplateId' => $dlttempid,
        ];

        $query = http_build_query($params);
        $url = "http://www.bulksms.saakshisoftware.in/api/mt/SendSMS?" . $query;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
