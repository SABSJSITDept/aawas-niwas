<?php

namespace App\Exports;

use App\Models\RoomFeatures;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomFeaturesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $hotelId;

    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function collection()
    {
        return RoomFeatures::with('hotel')
            ->where('hotel_id', $this->hotelId)
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->hotel->hotel_name ?? 'N/A',         // 👈 hotel name
            $row->room_number,
            $row->ac ? 'Yes' : 'No',
            $row->attach_bath ? 'Yes' : 'No',
            $row->toilet_type,
        ];
    }

    public function headings(): array
    {
        return ['Hotel Name', 'Room Number', 'AC', 'Attach Bath', 'Toilet Type'];
    }
}
