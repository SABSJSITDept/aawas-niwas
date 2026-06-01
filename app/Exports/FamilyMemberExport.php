<?php

namespace App\Exports;

use App\Models\FamilyBooking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FamilyMemberExport implements FromCollection, WithMapping, WithHeadings
{
    protected $flatRows = [];

    public function collection()
    {
        $bookings = FamilyBooking::with('members')->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id + 100;
            $totalMembers = $booking->members->count() + 1; // ✅ Include head

            // Booking ID row
            $this->flatRows[] = ["Booking ID: $bookingId"];

            // Head row
            $this->flatRows[] = [
                'Head',
                $booking->name,
                $booking->father_name,
                $booking->age,
                $booking->phone,
                $booking->aadhar_number,
                $booking->travel_type,
                $booking->check_in_date,
                $booking->check_in_time,
                $booking->check_out_date,
                $booking->check_out_time,
            ];

            // Member rows
            foreach ($booking->members as $member) {
                $this->flatRows[] = [
                    'Member',
                    $member->name,
                    $member->father_name,
                    $member->age,
                    $member->mobile,
                    $member->aadhar_number,
                    '', '', '', '', '', // Empty head fields
                ];
            }

            // Total row
            $this->flatRows[] = ["Total Members: $totalMembers"];
            $this->flatRows[] = []; // Spacing row
        }

        return collect($this->flatRows);
    }

    public function map($row): array
    {
        return $row;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Name',
            'Father Name',
            'Age',
            'Phone',
            'Aadhar Number',
            'Travel Type',
            'Check-in Date',
            'Check-in Time',
            'Check-out Date',
            'Check-out Time',
        ];
    }
}
