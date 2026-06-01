<?php

namespace App\Exports;

use App\Models\GroupBooking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GroupMemberExport implements FromCollection, WithMapping, WithHeadings
{
    protected $flatRows = [];

    public function collection()
    {
        $bookings = GroupBooking::with('members')->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id + 100;
            $totalMembers = $booking->members->count() + 1; // Include group head

            // ✅ Booking ID on top
            $this->flatRows[] = ["Booking ID: $bookingId"];

            // ✅ Group Head row
            $this->flatRows[] = [
                'Group Head',
                $booking->name,
                $booking->father_name,
                $booking->phone,
                $booking->aadhar_number,
                $booking->mid,
                $booking->travel_type,
                $booking->check_in_date,
                $booking->check_in_time,
                $booking->check_out_date,
                $booking->check_out_time,
                $booking->total_persons,
                $booking->status,
            ];

            // ✅ Member rows
            foreach ($booking->members as $member) {
                $this->flatRows[] = [
                    'Member',
                    $member->name,
                    $member->father_name,
                    $member->mobile_number,
                    $member->aadhar_number,
                    $member->mid,
                    '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                ];
            }

            // ✅ Total Members row
            $this->flatRows[] = ["Total Members: $totalMembers"];

            // ✅ Spacing row
            $this->flatRows[] = [];
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
            'Phone / Mobile',
            'Aadhar Number',
            'MID',
            'Travel Type',
            'Check-in Date',
            'Check-in Time',
            'Check-out Date',
            'Check-out Time',
            'Total Persons',
            'Status'
        ];
    }
}
