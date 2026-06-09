<?php

namespace App\Exports;

use App\Models\HotelDetails;
use App\Models\BookedRoom;
use App\Models\RoomFeatures;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomsExportAll implements FromArray, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        $data = [];

        $hotelsQuery = HotelDetails::where('status', 'active')
            ->with(['roomCategories.category']);
            
        if (!empty($this->filters['hotel_id'])) {
            $hotelsQuery->where('id', $this->filters['hotel_id']);
        }
        
        $hotels = $hotelsQuery->get();
        $fromDate = $this->filters['from_date'] ?? now()->toDateString();
        $toDate = $this->filters['to_date'] ?? now()->toDateString();

        // Global totals
        $totalRoomsGlobal = 0;
        $totalAvailableGlobal = 0;
        $totalBookedGlobal = 0;
        $totalCapacityGlobal = 0;

        foreach ($hotels as $hotel) {
            $data[] = ['Hotel Name: ' . $hotel->hotel_name];
            $data[] = [
                'Room No',
                'Category',
                'Floor',
                'Beds',
                'Extra Capacity',
                'Total Capacity',
                'Booked',
                'Available',
            ];

            // Per-hotel totals
            $hotelRoomsCount = 0;
            $hotelBooked = 0;
            $hotelAvailable = 0;
            $hotelTotalCapacity = 0;

            foreach ($hotel->roomCategories as $category) {
                $roomNumbers = explode(',', $category->room_number);

                foreach ($roomNumbers as $room) {
                    $room = trim($room);

                    // ✅ Check if room is active in room_features
                    $isActive = RoomFeatures::where('hotel_id', $hotel->id)
                        ->where('room_number', $room)
                        ->where('status', 'active')
                        ->exists();

                    if (!$isActive) {
                        continue; // ❌ Skip this room
                    }

                    $bookedQuery = BookedRoom::where('hotel_id', $hotel->id)
                        ->where('room_number', $room);

                    if ($fromDate && $toDate) {
                        $bookedQuery->where(function($q) use ($fromDate, $toDate) {
                            $q->where(function($sq) use ($fromDate, $toDate) {
                                $sq->whereDate('check_in_date', '<=', $toDate)
                                   ->whereDate('check_out_date', '>=', $fromDate);
                            })->orWhere(function($sq) {
                                $sq->whereNull('check_in_date')->whereNull('check_out_date');
                            });
                        });
                    }

                    $booked = $bookedQuery->sum('total_capacity');

                    $available = $category->total_capacity - $booked;

                    $data[] = [
                        $room,
                        $category->category->category_name ?? 'N/A',
                        $category->floor,
                        $category->beds ?? 'N/A',
                        $category->extra_capacity ?? 'N/A',
                        $category->total_capacity,
                        $booked,
                        $available,
                    ];

                    $hotelRoomsCount++;
                    $hotelBooked += $booked;
                    $hotelAvailable += $available;
                    $hotelTotalCapacity += $category->total_capacity;
                }
            }

            // Per-hotel totals row
            $data[] = [
                'Total Rooms: ' . $hotelRoomsCount,
                '',
                '',
                '',
                '',
                'Total: ' . $hotelTotalCapacity,
                'Booked: ' . $hotelBooked,
                'Available: ' . $hotelAvailable,
            ];

            $data[] = ['']; // Blank row between hotels

            // Add to global totals
            $totalRoomsGlobal += $hotelRoomsCount;
            $totalAvailableGlobal += $hotelAvailable;
            $totalBookedGlobal += $hotelBooked;
            $totalCapacityGlobal += $hotelTotalCapacity;
        }

        // Add overall totals at the end
        $data[] = [''];
        $data[] = ['Overall Summary'];
        $data[] = [
            'Total Rooms: ' . $totalRoomsGlobal,
            '',
            '',
            '',
            '',
            'Total: ' . $totalCapacityGlobal,
            'Booked: ' . $totalBookedGlobal,
            'Available: ' . $totalAvailableGlobal,
        ];

        return $data;
    }

    public function headings(): array
    {
        return []; // Headings are added manually inside array
    }
}
