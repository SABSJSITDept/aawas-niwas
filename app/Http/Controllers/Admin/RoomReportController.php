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
    $bookingType = $room->booking_type ?? '';
    
    if (!$bookingType) {
        if (strpos($room->booking_id, 'V-') === 0) {
            $bookingType = 'vip';
        } elseif (strpos($room->booking_id, 'F-') === 0) {
            $bookingType = 'family';
        } elseif (strpos($room->booking_id, 'G-') === 0) {
            $bookingType = 'group';
        }
    }

    $model = null;
    try {
        if ($bookingType === 'vip') {
            $model = Form::find($room->booking_id);
        } elseif ($bookingType === 'family') {
            $model = FamilyBooking::where('booking_id', $room->booking_id)->first() ?? FamilyBooking::find($room->booking_id);
        } elseif ($bookingType === 'group') {
            $model = GroupBooking::where('booking_id', $room->booking_id)->first() ?? GroupBooking::find($room->booking_id);
        } else {
            // Fallback guess
            $model = FamilyBooking::where('booking_id', $room->booking_id)->first()
                ?? GroupBooking::where('booking_id', $room->booking_id)->first()
                ?? Form::find($room->booking_id)
                ?? FamilyBooking::find($room->booking_id)
                ?? GroupBooking::find($room->booking_id);
        }
    } catch (\Exception $e) {
        // Fallback safely if any column is missing in older tables
    }

    if ($model) {
        $name = $model->name ?? '-';
        $phone = $model->phone ?? '-';
        $totalPersons = $model->total_persons ?? ($model instanceof Form ? '1' : '-');
        $checkin = $model->check_in_date ?? '-';
        $checkout = $model->check_out_date ?? '-';
        $checkinTime = $model->check_in_time ?? '-';
        $checkoutTime = $model->check_out_time ?? '-';
    }

    // Fallback to BookedRoom data if still empty
    if ($phone === '-') $phone = $room->mobile_number ?? '-';
    if ($totalPersons === '-') $totalPersons = $room->total_capacity ?? '-';
    if ($checkin === '-') $checkin = $room->check_in_date ?? '-';
    if ($checkout === '-') $checkout = $room->check_out_date ?? '-';

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
