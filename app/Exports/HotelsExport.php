<?php

namespace App\Exports;

use App\Models\HotelDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HotelsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return HotelDetails::select('id', 'hotel_name', 'incharge_name', 'contact_number', 'total_rooms', 'common_bath', 'lift', 'generator', 'address')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Hotel Name',
            'Incharge Name',
            'Contact Number',
            'Total Rooms',
            'Common Bath',
            'Lift',
            'Generator',
            'Address',
        ];
    }
}

