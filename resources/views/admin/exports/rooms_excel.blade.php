<table>
    <thead>
        <tr>
            <th>Room No</th>
            <th>Category</th>
            <th>Floor</th>
            <th>Total Capacity</th>
            <th>Booked</th>
            <th>Available</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            @php
                $roomNumbers = explode(',', $category->room_number);
            @endphp
            @foreach($roomNumbers as $room)
                @php
                    $room = trim($room);
                    $roomData = \App\Models\RoomCategory::where('hotel_id', $category->hotel_id)
                                ->whereRaw("FIND_IN_SET('$room', room_number)")
                                ->first();
                    $capacity = $roomData ? $roomData->total_capacity : 0;
                    $booked = \App\Models\BookedRoom::where('hotel_id', $category->hotel_id)
                                ->where('room_number', $room)
                                ->sum('total_capacity');
                    $available = $capacity - $booked;
                @endphp
                <tr>
                    <td>{{ $room }}</td>
                    <td>{{ $category->category->category_name ?? 'N/A' }}</td>
                    <td>{{ $category->floor }}</td>
                    <td>{{ $capacity }}</td>
                    <td>{{ $booked }}</td>
                    <td>{{ $available }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
