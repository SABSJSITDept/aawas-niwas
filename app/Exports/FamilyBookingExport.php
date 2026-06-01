<?php

namespace App\Exports;

use App\Models\FamilyBooking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FamilyBookingExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Eager load relationships for city, state, and aanchal
        return FamilyBooking::with(['cityName', 'stateName', 'aanchalName'])->get();
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->name,
            $booking->father_name,
            $booking->age,
            $booking->phone,
            $booking->mid,
            $booking->aadhar_number,
            $booking->cityName->city_name ?? 'N/A',
            $booking->stateName->state_name ?? 'N/A',
            $booking->aanchalName->name ?? 'N/A',
            $booking->travel_type,
            $booking->check_in_date,
            $booking->check_in_time,
            $booking->check_out_date,
            $booking->check_out_time,
            $booking->family_coming == 1 ? 'Yes' : 'No',
            $booking->no_of_people,
            $booking->no_of_children,
            $booking->total_male,
            $booking->total_female,
            $booking->sixty_plus_members,
            $booking->sixty_plus_male,
            $booking->sixty_plus_female,
            $booking->total_persons,
            $booking->booking_type,
            $booking->status,
            $booking->created_at,
            $booking->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Father Name',
            'Age',
            'Phone',
            'mid',
            'Aadhar Number',
            'City',
            'State',
            'Aanchal',
            'Travel Type',
            'Check-in Date',
            'Check-in Time',
            'Check-out Date',
            'Check-out Time',
            'Family Coming',
            'No. of People',
            'No. of Children',
            'Total Male',
            'Total Female',
            '60+ Members',
            '60+ Male',
            '60+ Female',
            'Total Persons',
            'Booking Type',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}
