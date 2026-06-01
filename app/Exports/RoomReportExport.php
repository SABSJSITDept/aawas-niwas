<?php

namespace App\Exports;

use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\HotelDetails;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomReportExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Room Number',
            'Hotel Name',
            'Booking ID',
            'Booked By',
            'Phone',
            'Total Persons',
            'Check-in Date',
            'Check-in Time',
            'Check-out Date',
            'Check-out Time',
        ];
    }

    public function collection()
    {
        $query = BookedRoom::query();

        if (!empty($this->filters['room_number'])) {
            $query->where('room_number', 'like', '%' . $this->filters['room_number'] . '%');
        }

        if (!empty($this->filters['hotel_name'])) {
            $query->whereHas('hotel', function ($q) {
                $q->where('hotel_name', 'like', '%' . $this->filters['hotel_name'] . '%');
            });
        }

        $bookedRooms = $query->latest()->get();

        $rows = [];

        foreach ($bookedRooms as $room) {
            $name = '-';
            $phone = '-';
            $totalPersons = '-';
            $checkin = '-';
            $checkout = '-';
            $hotelName = '-';

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
                        $totalPersons = $vip->total_persons ?? '1';
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
            if (!empty($this->filters['name']) && stripos($name, $this->filters['name']) === false) {
                continue;
            }

            if (!empty($this->filters['check_in_date']) && $checkin !== $this->filters['check_in_date']) {
                continue;
            }

            if (!empty($this->filters['check_out_date']) && $checkout !== $this->filters['check_out_date']) {
                continue;
            }

            $rows[] = [
                'Room Number'     => $room->room_number,
                'Hotel Name'      => $hotelName,
                'Booking ID'      => $room->booking_id,
                'Booked By'       => $name,
                'Phone'           => $phone,
                'Total Persons'   => $totalPersons,
                'Check-in Date'   => $checkin,
                'Check-in Time'   => $checkinTime ?? '-',
                'Check-out Date'  => $checkout,
                'Check-out Time'  => $checkoutTime ?? '-',
            ];
        }

        return collect($rows);
    }
}
