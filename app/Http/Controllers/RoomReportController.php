<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\HotelDetails;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomReportController extends Controller
{
    public function roomBookingReport(Request $request)
    {
        $hotels = HotelDetails::all();
        $reportData = [];

        if ($request->has('check_in_date')) {
            $hotelId = $request->hotel_id;
            $checkInDate = $request->check_in_date;

            $bookings = BookedRoom::when($hotelId, function ($query) use ($hotelId) {
                    return $query->where('hotel_id', $hotelId);
                })
                ->where('check_in_date', $checkInDate)
                ->with('hotel') // eager load hotel
                ->get()
                ->groupBy(function ($item) {
                    return $item->booking_id;
                });

            foreach ($bookings as $groupedBookings) {
                $firstBooking = $groupedBookings->first();

                $bookedBy = 'N/A';
                $mobileNumber = 'N/A';
                $totalPersons = 0;

                // Determine booking type from booking_id prefix
                $bookingType = '';
                if (strpos($firstBooking->booking_id, 'V-') === 0) {
                    $bookingType = 'vip';
                } elseif (strpos($firstBooking->booking_id, 'F-') === 0) {
                    $bookingType = 'family';
                } elseif (strpos($firstBooking->booking_id, 'G-') === 0) {
                    $bookingType = 'group';
                }

                if ($bookingType == 'vip') {
                    $vip = Form::where('booking_id', $firstBooking->booking_id)->first();
                    if ($vip) {
                        $bookedBy = $vip->name ?? 'N/A';
                        $mobileNumber = $vip->phone ?? 'N/A';
                        $totalPersons = $vip->total_persons ?? 1;
                    }
                } elseif ($bookingType == 'family') {
                    $family = FamilyBooking::where('booking_id', $firstBooking->booking_id)->first();
                    if ($family) {
                        $bookedBy = $family->name ?? 'N/A';
                        $mobileNumber = $family->phone ?? 'N/A';
                        $totalPersons = $family->total_persons ?? 1;
                    }
                } elseif ($bookingType == 'group') {
                    $group = GroupBooking::where('booking_id', $firstBooking->booking_id)->first();
                    if ($group) {
                        $bookedBy = $group->name ?? 'N/A';
                        $mobileNumber = $group->phone ?? 'N/A';
                        $totalPersons = $group->total_persons ?? 1;
                    }
                }

                $roomNumbers = $groupedBookings->pluck('room_number')->unique()->implode(', ');

                $reportData[] = [
                    'hotel_name'     => optional($firstBooking->hotel)->hotel_name,
                    'room_number'    => $roomNumbers,
                    'booked_by'      => $bookedBy,
                    'phone'          => $mobileNumber,
                    'check_in_date'  => $firstBooking->check_in_date,
                    'check_out_date' => $firstBooking->check_out_date,
                    'total_persons'  => $totalPersons,
                ];
            }
        }

        return view('admin.room-booking-report', compact('hotels', 'reportData'));
    }

    public function roomBookingReportPdf(Request $request)
    {
        $reportData = [];

        if ($request->has('check_in_date')) {
            $hotelId = $request->hotel_id;
            $checkInDate = $request->check_in_date;

            $bookings = BookedRoom::when($hotelId, function ($query) use ($hotelId) {
                    return $query->where('hotel_id', $hotelId);
                })
                ->where('check_in_date', $checkInDate)
                ->with('hotel') // eager load
                ->get()
                ->groupBy(function ($item) {
                    return $item->booking_id;
                });

            foreach ($bookings as $groupedBookings) {
                $firstBooking = $groupedBookings->first();

                $bookedBy = 'N/A';
                $mobileNumber = 'N/A';
                $totalPersons = 0;

                // Determine booking type from booking_id prefix
                $bookingType = '';
                if (strpos($firstBooking->booking_id, 'V-') === 0) {
                    $bookingType = 'vip';
                } elseif (strpos($firstBooking->booking_id, 'F-') === 0) {
                    $bookingType = 'family';
                } elseif (strpos($firstBooking->booking_id, 'G-') === 0) {
                    $bookingType = 'group';
                }

                if ($bookingType == 'vip') {
                    $vip = Form::where('booking_id', $firstBooking->booking_id)->first();
                    if ($vip) {
                        $bookedBy = $vip->name ?? 'N/A';
                        $mobileNumber = $vip->phone ?? 'N/A';
                        $totalPersons = $vip->total_persons ?? 1;
                    }
                } elseif ($bookingType == 'family') {
                    $family = FamilyBooking::where('booking_id', $firstBooking->booking_id)->first();
                    if ($family) {
                        $bookedBy = $family->name ?? 'N/A';
                        $mobileNumber = $family->phone ?? 'N/A';
                        $totalPersons = $family->total_persons ?? 1;
                    }
                } elseif ($bookingType == 'group') {
                    $group = GroupBooking::where('booking_id', $firstBooking->booking_id)->first();
                    if ($group) {
                        $bookedBy = $group->name ?? 'N/A';
                        $mobileNumber = $group->phone ?? 'N/A';
                        $totalPersons = $group->total_persons ?? 1;
                    }
                }

                $roomNumbers = $groupedBookings->pluck('room_number')->unique()->implode(', ');

                $reportData[] = [
                    'hotel_name'     => optional($firstBooking->hotel)->hotel_name,
                    'room_number'    => $roomNumbers,
                    'booked_by'      => $bookedBy,
                    'mobile_number'  => $mobileNumber,
                    'check_in_date'  => $firstBooking->check_in_date,
                    'check_out_date' => $firstBooking->check_out_date,
                    'total_persons'  => $totalPersons,
                ];
            }
        }

        $totalPersons = array_sum(array_column($reportData, 'total_persons'));

        $pdf = Pdf::loadView('room-booking-report-pdf', compact('reportData', 'totalPersons'))
            ->setOptions(['defaultFont' => 'NotoSansDevanagari_SemiCondensed-Black']);

        $fileName = $request->hotel_id ? 'hotel_' . $request->hotel_id . '_report.pdf' : 'all_hotels_report.pdf';

        return $pdf->download($fileName);
    }

    public function index()
    {
        return view('room_report');
    }

    public function fetch(Request $request)
    {
        $checkIn = $request->check_in_date;
        $checkOut = $request->check_out_date;   

        $data = [];
        $hasFormStatus = Schema::hasColumn('forms', 'status');
        $hasFamilyStatus = Schema::hasColumn('family_booking', 'status');
        $hasGroupStatus = Schema::hasColumn('group_bookings', 'status');

        // Helper function to build query based on dates
        $buildQuery = function ($query) use ($checkIn, $checkOut) {
            if ($checkIn && !$checkOut) {
                return $query->whereDate('check_in_date', $checkIn);
            } elseif (!$checkIn && $checkOut) {
                return $query->whereDate('check_out_date', $checkOut);
            } elseif ($checkIn && $checkOut) {
                return $query->whereDate('check_in_date', $checkIn)->whereDate('check_out_date', $checkOut);
            } else {
                return $query; // if neither, return all, but form requires at least one
            }
        };

        // Forms Table
        $formsQuery = Form::query();
        if ($hasFormStatus) {
            $formsQuery->whereIn('status', ['pending', 'completed']);
        }
        $forms = $buildQuery($formsQuery)->get();

        foreach ($forms as $form) {
            $data[] = [
                'name' => $form->name,
                'phone' => $form->phone,
                'total_persons' => $form->total_persons,
                'check_in_date' => $form->check_in_date,
                'check_in_time' => $form->check_in_time,
                'check_out_date' => $form->check_out_date,
                'check_out_time' => $form->check_out_time,
                'status' => $form->status ?? '-',
            ];
        }

        // Family Booking
        $familiesQuery = FamilyBooking::query();
        if ($hasFamilyStatus) {
            $familiesQuery->whereIn('status', ['pending', 'completed']);
        }
        $families = $buildQuery($familiesQuery)->get();

        foreach ($families as $fb) {
            $data[] = [
                'name' => $fb->name,
                'phone' => $fb->phone,
                'total_persons' => $fb->total_persons,
                'check_in_date' => $fb->check_in_date,
                'check_in_time' => $fb->check_in_time,
                'check_out_date' => $fb->check_out_date,
                'check_out_time' => $fb->check_out_time,
                'status' => $fb->status ?? '-',
            ];
        }

        // Group Booking
        $groupsQuery = GroupBooking::query();
        if ($hasGroupStatus) {
            $groupsQuery->whereIn('status', ['pending', 'completed']);
        }
        $groups = $buildQuery($groupsQuery)->get();

        foreach ($groups as $gb) {
            $data[] = [
                'name' => $gb->name,
                'phone' => $gb->phone,
                'total_persons' => $gb->total_persons,
                'check_in_date' => $gb->check_in_date,
                'check_in_time' => $gb->check_in_time,
                'check_out_date' => $gb->check_out_date,
                'check_out_time' => $gb->check_out_time,
                'status' => $gb->status ?? '-',
            ];
        }

        return view('room_report', compact('data'));
    }

    public function downloadPdf(Request $request)
    {
        $request->merge(['pdf' => true]);
        $data = $this->fetch($request)->getData()['data'];
        $totalPersons = array_sum(array_column($data, 'total_persons'));

        $pdf = Pdf::loadView('pdf.room_report_pdf', compact('data', 'totalPersons'));
        return $pdf->download('Room_Report.pdf');
    }




    public function roomBookingSummaryReport(Request $request)
{
    $summaryData = [];

    if ($request->has('check_in_date')) {
        $checkInDate = $request->check_in_date;

        $bookings = BookedRoom::where('check_in_date', $checkInDate)
            ->with('hotel')
            ->get();

        foreach ($bookings as $booking) {
            $hotelName = optional($booking->hotel)->hotel_name ?? 'N/A';
            $persons = 0;

            // Determine booking type from booking_id prefix
            $bookingType = '';
            if (strpos($booking->booking_id, 'V-') === 0) {
                $bookingType = 'vip';
            } elseif (strpos($booking->booking_id, 'F-') === 0) {
                $bookingType = 'family';
            } elseif (strpos($booking->booking_id, 'G-') === 0) {
                $bookingType = 'group';
            }

            if ($bookingType === 'vip') {
                $vip = Form::where('booking_id', $booking->booking_id)->first();
                $persons = $vip->total_persons ?? 1;
            } elseif ($bookingType === 'family') {
                $family = FamilyBooking::where('booking_id', $booking->booking_id)->first();
                $persons = $family->total_persons ?? 1;
            } elseif ($bookingType === 'group') {
                $group = GroupBooking::where('booking_id', $booking->booking_id)->first();
                $persons = $group->total_persons ?? 1;
            }

            if (!isset($summaryData[$hotelName])) {
                $summaryData[$hotelName] = 0;
            }

            $summaryData[$hotelName] += $persons;
        }
    }

    return view('admin.room-booking-summary-report', compact('summaryData'));
}

}
