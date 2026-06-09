@extends('admin.layout')
@section('content')
<div class="container">
    <h2 class="text-center">Room Allotment</h2>

    {{-- Booking Info --}}
    <input type="hidden" id="booking_id" value="{{ $booking_id }}">
    <input type="hidden" id="booking_type" value="{{ $booking_type }}">
    <input type="hidden" id="total_persons" value="{{ $total_persons }}">
    <input type="hidden" name="check_in_date" value="{{ $checkInDate }}">
    <input type="hidden" name="check_out_date" value="{{ $checkOutDate }}">
    <input type="hidden" name="mobile_number" value="{{ $mobileNumber }}">



    {{-- Hotel Selection --}}
    <h4 class="mt-4">Select a Hotel:</h4>
    <div class="hotel-selection-container mb-4">
        @foreach($hotels as $hotel)
            <div class="hotel-card {{ $selectedHotelId == $hotel->id ? 'selected' : '' }}" 
                 onclick="window.location='{{ route('alot.room', ['booking_id' => $booking_id, 'booking_type' => $booking_type, 'hotel_id' => $hotel->id]) }}'">
                <strong>{{ $hotel->hotel_name }}</strong>
            </div>
        @endforeach
    </div>

    {{-- Rooms Display --}}
    @if($selectedHotelId && count($categories))
        <h5 class="mt-4 mb-2">Click rooms to allot (Total Required: {{ $total_persons }})</h5>
        <div class="mb-2">
            Total Selected Capacity: <span id="selectedCapacity">0</span> / {{ $total_persons }}
        </div>

        <form method="POST" action="{{ route('alot.room.store') }}" id="roomForm">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking_id }}">
            <input type="hidden" name="booking_type" value="{{ $booking_type }}">
            <input type="hidden" name="hotel_id" value="{{ $selectedHotelId }}">
            <input type="hidden" name="rooms_json" id="rooms_json">
            <input type="hidden" name="check_in_date" value="{{ $checkInDate }}">
            <input type="hidden" name="check_out_date" value="{{ $checkOutDate }}">
            <input type="hidden" name="mobile_number" value="{{ $mobileNumber }}">


            @foreach ($categories as $category)
                <h4 class="mt-4 mb-2 text-lg font-bold">{{ $category->category->category_name ?? 'No Name' }} (Floor: {{ $category->floor }})</h4>

                @php
                    $roomNumbers = explode(',', $category->room_number);
                @endphp

                <div class="room-selection-container">
                    @foreach ($roomNumbers as $room)
                        @php
                            $room = trim($room);
                            $booked = \App\Models\BookedRoom::where('hotel_id', $category->hotel_id)
                                        ->where('room_number', $room)
                                        ->sum('total_capacity');

                            $available = $category->total_capacity - $booked;
                            $status = '';
                            if ($available <= 0) {
                                $status = 'full';
                            } elseif ($available < $category->total_capacity) {
                                $status = 'partial';
                            } else {
                                $status = 'empty';
                            }
                        @endphp

                        <div class="room-selection-container">
                            <div class="room-circle {{ $status }}" 
                                 data-room="{{ $room }}" 
                                 data-capacity="{{ $available }}">
                                Room {{ $room }} <br>
                                {{ $status === 'full' ? "Full" : "$available left" }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <div>Total Required: <span id="totalRequiredDisplay"></span></div>

            <div class="d-flex justify-content-center mt-4">
            <button type="submit" id="allotButton" class="btn btn-success" disabled>Allot Rooms</button>
            </div>
        </form>
    @endif
</div>

{{-- CSS --}}
<style>
/* General Styling for Hotel Cards */
.hotel-selection-container {
    display: flex;
    overflow-x: scroll;
    gap: 16px;
    padding: 12px 0;
}
/* Styling for Room Circles */
.room-selection-container {
    display: flex;
    overflow-x: scroll;
    gap: 16px;
    padding: 12px 0;
}

.room-circle {
    width: 60px;   /* Reduced size */
    height: 60px;  /* Reduced size */
    border-radius: 50%;
    background-color: #e0e0e0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    border: 2px solid #888;
    cursor: pointer;
    padding: 4px;
    text-align: center;
    transition: background-color 0.3s, border-color 0.3s, color 0.3s;
    font-size: 12px;  /* Reduced text size */
}

/* Full, Partial, and Empty Status Colors */
.room-circle.full {
    background-color: red;
    color: white;
    border-color: darkred;
}

.room-circle.partial {
    background-color: orange;
    color: white;
    border-color: darkorange;
}

.room-circle.empty {
    background-color: green;
    color: white;
    border-color: darkgreen;
}

/* Hover effect for the room circle */
.room-circle:hover {
    background-color: #f1f1f1;
}

/* When selected, change background color */
.room-circle.selected {
    background-color: #4caf50;
    color: white;
    border-color: #388e3c;
}

/* Disabled (Full) room circles */
.room-circle.disabled {
    background-color: #ccc;
    border-color: #999;
    color: #666;
    pointer-events: none;
    cursor: not-allowed;
}

.hotel-card {
    padding: 12px 20px;
    border: 2px solid #ccc;
    border-radius: 12px;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
    text-align: center;
    width: 200px;
    margin: 0 8px;
}
.hotel-card:hover {
    background-color: #e9ecef;
}
.hotel-card.selected {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
}

/* Button Styling */
.btn-success {
    background-color: #28a745;
    color: white;
    border-radius: 5px;
    padding: 12px 30px;
    font-size: 18px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn-success:hover {
    background-color: #218838;
}
</style>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let totalRequired = parseInt(document.getElementById('total_persons').value);
let selectedRooms = [];
let totalSelectedCapacity = 0;

document.getElementById('totalRequiredDisplay').textContent = totalRequired;

function updateAllotButtonState() {
    const button = document.getElementById('allotButton');
    button.disabled = totalSelectedCapacity < totalRequired;
}

document.querySelectorAll('.room-circle:not(.disabled)').forEach(room => {
    room.addEventListener('click', function () {
        const roomNumber = this.dataset.room;
        const roomCapacity = parseInt(this.dataset.capacity);

        const index = selectedRooms.findIndex(r => r.room_number === roomNumber);
        if (index > -1) {
            totalSelectedCapacity -= selectedRooms[index].capacity;
            selectedRooms.splice(index, 1);
            this.classList.remove('selected');
        } else {
            const remaining = totalRequired - totalSelectedCapacity;

            if (remaining <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Reached',
                    text: 'Total capacity already fulfilled!',
                });
                return;
            }

            const assignedCapacity = Math.min(roomCapacity, remaining);
            selectedRooms.push({ room_number: roomNumber, capacity: assignedCapacity });
            totalSelectedCapacity += assignedCapacity;
            this.classList.add('selected');
        }

        document.getElementById('selectedCapacity').textContent = totalSelectedCapacity;
        document.getElementById('rooms_json').value = JSON.stringify(selectedRooms);
        updateAllotButtonState();
    });
});

document.getElementById('roomForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    const data = JSON.parse(document.getElementById('rooms_json').value || '[]');

    if (data.length === 0 || totalSelectedCapacity < totalRequired) {
        Swal.fire({
            icon: 'error',
            title: 'Cannot Submit',
            text: 'Please select rooms to fulfill total required capacity.',
        });
        return;
    }

    const roomList = data.map(r => r.room_number).join(', ');

    Swal.fire({
        title: 'Confirm Room Allotment',
        html: `You have selected the following rooms:<br><strong>${roomList}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Allot',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            data.forEach((room, index) => {
                const rn = document.createElement('input');
                rn.type = 'hidden';
                rn.name = `rooms[${index}][room_number]`;
                rn.value = room.room_number;
                form.appendChild(rn);

                const cp = document.createElement('input');
                cp.type = 'hidden';
                cp.name = `rooms[${index}][capacity]`;
                cp.value = room.capacity;
                form.appendChild(cp);
            });

            form.submit();
        }
    });
});

// Adding SweetAlert after booking confirmation and redirection
document.addEventListener('DOMContentLoaded', function() {
    const message = '{{ session("success") }}';
    if (message) {
        Swal.fire({
            icon: 'success',
            title: 'Booking Done Successfully!',
            text: message,
            confirmButtonText: 'OK',
        }).then(() => {
            window.location.href = '{{ route("admin.dashboard") }}';
        });
    }
});
</script>
@endsection
    