<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\HotelDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\HtmlToImageService;
use Illuminate\Support\Facades\Log;

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

        if (preg_match('/^\d{10}$/', $bookingId)) {
            // It's a mobile number. Search for the booking.
            $booking = FamilyBooking::where('phone', $bookingId)->latest()->first();
            if ($booking) {
                $bookingType = 'family';
                $numericId = $booking->id;
                $bookingId = 'F-' . ($numericId + 100);
            } else {
                $booking = GroupBooking::where('phone', $bookingId)->latest()->first();
                if ($booking) {
                    $bookingType = 'group';
                    $numericId = $booking->id;
                    $bookingId = 'G-' . ($numericId + 100);
                } else {
                    $booking = Form::where('phone', $bookingId)->latest()->first();
                    if ($booking) {
                        $bookingType = 'vip';
                        $numericId = $booking->id;
                        $bookingId = 'V-' . ($numericId + 100);
                    } else {
                        return redirect()->back()->with('error', 'No booking found for this mobile number.');
                    }
                }
            }
        } elseif (strpos($bookingId, 'F-') === 0) {
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
            return redirect()->back()->with('error', 'Booking details not found / कोई बुकिंग नहीं मिली।');
        }

        // Fetch booked rooms
        $rooms = BookedRoom::where('booking_id', $bookingId)->get();

        if ($rooms->isEmpty()) {
            $roomNumbers = 'No Room Allotted / कमरा आवंटित नहीं';
            $hotelName = '-';
            $hotelAddress = '-';
            $contactPerson = '-';
            $googleMapsLink = null;
        } else {
            // All room numbers
            $roomNumbers = $rooms->pluck('room_number')->implode(', ');

            // Hotel info
            $hotel = HotelDetails::find($rooms->first()->hotel_id);
            $hotelName = $hotel->hotel_name ?? '-';
            $hotelAddress = $hotel->address ?? '-';
            $contactPerson = trim(($hotel->incharge_name ?? '') . ' - ' . ($hotel->contact_number ?? ''));
            $googleMapsLink = $hotel->google_maps_link ?? null;
        }

        // Prepare data
        $data = [
            'booking_id'     => $bookingId,
            'logo'           => public_path('images/chaturmaslogo.png'),
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

        $filename = 'booking-confirmation-' . $bookingId . '-' . date('Y-m-d') . '.pdf';

        return $this->generatePdfResponse($data, $filename, $action);
    }

    private function generatePdfResponse(array $data, string $filename, string $action)
    {
        try {
            $imageService = app(HtmlToImageService::class);

            $html = view('booking-pdf-template', array_merge($data, [
                'renderForImage' => true,
                'logo_src' => $imageService->toDataUri($data['logo']),
                'logo2_src' => $imageService->toDataUri($data['logo2']),
            ]))->render();

            $imagePath = $imageService->convert($html, 'booking_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $data['booking_id']));
            $pdf = Pdf::loadView('booking-pdf-image', compact('imagePath'))
                ->setPaper('a4', 'portrait');
        } catch (\Throwable $e) {
            Log::warning('Booking image PDF failed, using direct PDF', [
                'booking_id' => $data['booking_id'],
                'error' => $e->getMessage(),
            ]);

            $pdf = Pdf::loadView('booking-pdf-template', $data);
        }

        return $action === 'view'
            ? $pdf->stream($filename)
            : $pdf->download($filename);
    }
}
