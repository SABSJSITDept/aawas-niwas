<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\HotelDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FamilyMember;
use App\Models\GroupMember;

class BookingPdfController extends Controller
{
    public function generate(Request $request)
    {
        // Validate input - now only booking_id is required
        $request->validate([
            'booking_id' => 'required|string',
        ]);

        $action = $request->get('action', 'download'); // 'view' or 'download'
        $bookingId = $request->booking_id;

        // Auto-detect booking type from booking_id prefix
        $bookingType = null;
        $numericId = null;

        if (strpos($bookingId, 'F-') === 0) {
            $bookingType = 'family';
            $numericId = intval(substr($bookingId, 2)) - 100; // Apply offset for family bookings
        } elseif (strpos($bookingId, 'V-') === 0) {
            $bookingType = 'vip';
            $numericId = intval(substr($bookingId, 2)) - 100; // Apply offset for VIP bookings
        } elseif (strpos($bookingId, 'G-') === 0) {
            $bookingType = 'group';
            $numericId = intval(substr($bookingId, 2)) - 100; // Apply offset for group bookings
        } else {
            return redirect()->back()->with('error', 'Invalid booking ID format. Use F-XXX for Family, V-XXX for VIP, or G-XXX for Group bookings.');
        }

        // Fetch booked rooms
        $rooms = BookedRoom::where('booking_id', $bookingId)->get();

       if ($rooms->isEmpty()) {
            // Still generate a minimal PDF
            $data = [
                'booking_id'     => $bookingId,
                'logo'           => public_path('images/logo.png'),
                'logo2'          => public_path('images/logo.png'),
                'name'           => '-',
                'mobile'         => '-',
                'total_members'  => 0,
                'male'           => 0,
                'female'         => 0,
                'check_in'       => '-',
                'check_out'      => '-',
                'room_number'    => 'No Room Allotted',
                'hotel_name'     => '-',
                'hotel_address'  => '-',
                'contact_person' => '-',
            ];

            $pdf = Pdf::loadView('booking-pdf-template', $data);
            $filename = 'booking-' . $bookingId . '-no-rooms-' . date('Y-m-d') . '.pdf';

            return $request->get('action') === 'view'
                ? $pdf->stream($filename)
                : $pdf->download($filename);
        }


        // All room numbers
        $roomNumbers = $rooms->pluck('room_number')->implode(', ');

        // Hotel info
        $hotel = HotelDetails::find($rooms->first()->hotel_id);
        $hotelName = $hotel->hotel_name ?? '-';
        $hotelAddress = $hotel->address ?? '-';
        $contactPerson = trim(($hotel->incharge_name ?? '') . ' - ' . ($hotel->contact_number ?? ''));
        $googleMapsLink = $hotel->google_maps_link ?? null;

        // Booking info based on detected type
        switch ($bookingType) {
            case 'vip':
                $booking = Form::find($numericId);
                break;
            case 'family':
                $booking = FamilyBooking::find($numericId);
                break;
            case 'group':
                $booking = GroupBooking::find($numericId);
                break;
            default:
                $booking = null;
        }

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking details not found.');
        }

        // Prepare data
        $data = [
            'booking_id'     => $bookingId,
            'logo'           => public_path('images/logo_chaturmas01.png'),
            'logo2'          => public_path('images/logo.jpeg'),
            'name'           => $booking->name ?? '-',
            'mobile'         => $booking->phone ?? '-',
            'total_members'  => $booking->total_persons ?? 1,
            'male'           => $booking->total_male ?? 1,
            'female'         => $booking->total_female ?? 0,
            'check_in' => ($booking->check_in_date && $booking->check_in_time)
                ? \Carbon\Carbon::parse($booking->check_in_date . ' ' . $booking->check_in_time)->format('d-m-Y h:i A')
                : '-',
            'check_out' => ($booking->check_out_date && $booking->check_out_time)
                ? \Carbon\Carbon::parse($booking->check_out_date . ' ' . $booking->check_out_time)->format('d-m-Y h:i A')
                : '-',
            'room_number'    => $roomNumbers,
            'hotel_name'     => $hotelName,
            'hotel_address'  => $hotelAddress,
            'contact_person' => $contactPerson,
            'google_maps_link' => $googleMapsLink,
        ];

        // Load PDF view
        $pdf = Pdf::loadView('booking-pdf-template', $data);

        // Generate descriptive filename
        $filename = 'booking-confirmation-' . $bookingId . '-' . date('Y-m-d') . '.pdf';

        // Return view or download based on action
        return $action === 'view'
            ? $pdf->stream($filename)     // view in browser
            : $pdf->download($filename);  // force download
    }
}
