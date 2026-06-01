<?php

namespace App\Exports;

use App\Models\RoomCategory;
use App\Models\BookedRoom;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomsExport implements FromArray, WithHeadings
{
    protected $hotelId;

    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function array(): array
    {
        $data = [];

        $categories = RoomCategory::with('category')
            ->where('hotel_id', $this->hotelId)
            ->get();

        foreach ($categories as $category) {
            $roomNumbers = explode(',', $category->room_number);

            foreach ($roomNumbers as $room) {
                $room = trim($room);
                $booked = BookedRoom::where('hotel_id', $category->hotel_id)
                    ->where('room_number', $room)
                    ->sum('total_capacity');

                $available = $category->total_capacity - $booked;

                $data[] = [
                    'Room No'         => $room,
                    'Category'        => $category->category->category_name ?? 'N/A',
                    'Floor'           => $category->floor,
                    'Total Capacity'  => $category->total_capacity,
                    'Booked'          => $booked,
                    'Available'       => $available,
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Room No',
            'Category',
            'Floor',
            'Total Capacity',
            'Booked',
            'Available',
        ];
    }
}

