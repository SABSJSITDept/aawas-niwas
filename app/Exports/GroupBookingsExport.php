<?php

namespace App\Exports;

use App\Models\GroupBooking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GroupBookingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return GroupBooking::with(['cityName', 'stateName', 'aanchalName'])->get()->map(function ($booking) {
            return [
                'ID' => $booking->id,
                'Name' => $booking->name,
                'Father Name' => $booking->father_name,
                'Phone' => $booking->phone,
                'Aadhar Number' => $booking->aadhar_number,
                'MID' => $booking->mid,
                'City' => $booking->cityName->city_name ?? 'N/A',
                'State' => $booking->stateName->state_name ?? 'N/A',
                'Aanchal' => $booking->aanchalName->name ?? 'N/A',
                'Travel Type' => $booking->travel_type,
                'Check-in Date' => $booking->check_in_date,
                'Check-out Date' => $booking->check_out_date,
                'Check-in Time' => $booking->check_in_time,
                'Check-out Time' => $booking->check_out_time,
                'Total Members' => $booking->total_members,
                'Total Persons' => $booking->total_persons,
                'Total Male' => $booking->total_male,
                'Total Female' => $booking->total_female,
                '60+ Members' => $booking->sixty_plus_members,
                '60+ Male' => $booking->sixty_plus_male,
                '60+ Female' => $booking->sixty_plus_female,
                'Status' => $booking->status,
                'Booking Type' => $booking->booking_type,
                'Created At' => $booking->created_at,
                'Updated At' => $booking->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Father Name',
            'Phone',
            'Aadhar Number',
            'MID',
            'City',
            'State',
            'Aanchal',
            'Travel Type',
            'Check-in Date',
            'Check-out Date',
            'Check-in Time',
            'Check-out Time',
            'Total Members',
            'Total Persons',
            'Total Male',
            'Total Female',
            '60+ Members',
            '60+ Male',
            '60+ Female',
            'Status',
            'Booking Type',
            'Created At',
            'Updated At',
        ];
    }
}

