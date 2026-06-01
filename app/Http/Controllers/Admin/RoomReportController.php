<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\HotelDetails;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoomReportExport;
use Maatwebsite\Excel\Excel as ExcelFormat;

class RoomReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'room_number',
            'hotel_name',
            'name',
            'check_out_date',
            'check_in_date',
            'per_page'
        ]);

        $query = BookedRoom::query();

        if (!empty($filters['room_number'])) {
            $query->where('room_number', 'like', '%' . $filters['room_number'] . '%');
        }

        if (!empty($filters['hotel_name'])) {
            $query->whereHas('hotel', function ($q) use ($filters) {
                $q->where('hotel_name', 'like', '%' . $filters['hotel_name'] . '%');
            });
        }

        $perPage = $filters['per_page'] ?? 10;
        if ($perPage == 'all') {
            $bookedRooms = $query->latest()->get();
        } else {
            $bookedRooms = $query->latest()->paginate($perPage);
        }

$report = $bookedRooms->map(function ($room) use ($filters) {
    $name = '-';
    $checkin = '-';
    $checkout = '-';
    $hotelName = '-';
    $phone = '-';
    $totalPersons = '-';

    $hotel = HotelDetails::find($room->hotel_id);
    if ($hotel) {
        $hotelName = $hotel->hotel_name ?? '-';
    }

    // Determine booking type from booking_id prefix
    $bookingType = '';
    
    if (strpos($room->booking_id, 'V-') === 0) {
        $bookingType = 'vip';
    } elseif (strpos($room->booking_id, 'F-') === 0) {
        $bookingType = 'family';
    } elseif (strpos($room->booking_id, 'G-') === 0) {
        $bookingType = 'group';
    }

    switch ($bookingType) {
        case 'vip':
            $vip = Form::where('booking_id', $room->booking_id)->first();
            if ($vip) {
                $name = $vip->name ?? '-';
                $phone = $vip->phone ?? '-';
                $totalPersons = $vip->total_persons ?? '1'; // default 1 for vip
                $checkin = $vip->check_in_date ?? '-';
                $checkout = $vip->check_out_date ?? '-';
                $checkinTime = $vip->check_in_time ?? '-';
                $checkoutTime = $vip->check_out_time ?? '-';
            }
            break;

        case 'family':
            $family = FamilyBooking::where('booking_id', $room->booking_id)->first();
            if ($family) {
                $name = $family->name ?? '-';
                $phone = $family->phone ?? '-';
                $totalPersons = $family->total_persons ?? '-';
                $checkin = $family->check_in_date ?? '-';
                $checkout = $family->check_out_date ?? '-';
                $checkinTime = $family->check_in_time ?? '-';
                $checkoutTime = $family->check_out_time ?? '-';
            }
            break;

        case 'group':
            $group = GroupBooking::where('booking_id', $room->booking_id)->first();
            if ($group) {
                $name = $group->name ?? '-';
                $phone = $group->phone ?? '-';
                $totalPersons = $group->total_persons ?? '-';
                $checkin = $group->check_in_date ?? '-';
                $checkout = $group->check_out_date ?? '-';
                $checkinTime = $group->check_in_time ?? '-';
                $checkoutTime = $group->check_out_time ?? '-';
            }
            break;
    }

    // Filters
    if (!empty($filters['name']) && stripos($name, $filters['name']) === false) {
        return null;
    }

    if (!empty($filters['check_in_date']) && $checkin !== $filters['check_in_date']) {
        return null;
    }

    if (!empty($filters['check_out_date']) && $checkout !== $filters['check_out_date']) {
        return null;
    }

    return [
        'room_number'    => $room->room_number,
        'booking_id'     => $room->booking_id,
        'name'           => $name,
        'phone'          => $phone,
        'total_persons'  => $totalPersons,
        'check_in_date'  => $checkin,
        'check_out_date' => $checkout,
        'check_in_time'  => $checkinTime ?? '-',
        'check_out_time' => $checkoutTime ?? '-',
        'hotel_name'     => $hotelName,
    ];
})->filter();


        return view('admin.room-report', [
            'report' => $report,
            'filters' => $filters,
            'bookedRooms' => $bookedRooms instanceof \Illuminate\Pagination\LengthAwarePaginator ? $bookedRooms->appends($filters) : $bookedRooms,
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new RoomReportExport($request->all()), 'room_allotment_report.xlsx', ExcelFormat::XLSX);
    }
}
