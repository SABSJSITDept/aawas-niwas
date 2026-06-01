@extends('admin.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .room-block {
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h3 class="text-primary fw-bold mb-4"><i class="fas fa-plus-circle me-2"></i>Add Rooms</h3>

    <form action="{{ route('room.store', ['hotelId' => $hotel->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="floor" class="form-label">Floor</label> 
                <select id="floor" name="floor" class="form-select" required>
                    <option value="">Select Floor</option>
                    <option value="Underground">Underground</option>
                    <option value="Ground" selected>Ground</option>
                    @for ($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}th">{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label for="category" class="form-label">🛏️ Room Category</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="beds" class="form-label">Beds</label>
                <input type="number" id="beds" name="beds" class="form-control" min="1" value="1">
            </div>

            <div class="col-md-2">
                <label for="extra" class="form-label">Extra Capacity</label>
                <input type="number" id="extra" name="extra_capacity" class="form-control" min="0" value="0">
            </div>

            <div class="col-md-2">
                <label for="total" class="form-label">Total Capacity</label>
                <input type="number" id="total" name="total_capacity" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
                <label for="roomNumbers" class="form-label">Room Numbers (comma separated)</label>
                <input type="text" id="roomNumbers" name="room_number" class="form-control" placeholder="Eg: 101, 102, 103">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-primary w-100" onclick="generateRoomFields()">Generate Room Details</button>
            </div>
        </div>

        <div id="roomDetails"></div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success px-4" id="saveRoomsBtn" disabled>
                <i class="fas fa-save me-1"></i> Save Rooms
            </button>
        </div>
    </form>
</div>

<script>
    const beds = document.getElementById('beds');
    const extra = document.getElementById('extra');
    const total = document.getElementById('total');

    function updateTotal() {
        total.value = (parseInt(beds.value) || 0) + (parseInt(extra.value) || 0);
    }

    beds.addEventListener('input', updateTotal);
    extra.addEventListener('input', updateTotal);
    window.onload = updateTotal;

    function generateRoomFields() {
        const roomInput = document.getElementById('roomNumbers').value;
        const roomArray = roomInput.split(',').map(num => num.trim()).filter(num => num !== "");
        const roomDetails = document.getElementById('roomDetails');
        const saveBtn = document.getElementById('saveRoomsBtn');

        roomDetails.innerHTML = '';
        if (roomArray.length === 0) {
            saveBtn.disabled = true;
            return;
        }

        roomArray.forEach((roomNum, index) => {
            const block = document.createElement('div');
            block.className = 'room-block';
            block.innerHTML = `
                <h5 class="text-secondary mb-3">Room ${roomNum}</h5>
                <input type="hidden" name="rooms[${index}][room_number]" value="${roomNum}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label d-block">🌬️ AC Type</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][ac]" id="ac_yes_${index}" value="AC" checked>
                            <label class="form-check-label" for="ac_yes_${index}">❄️ AC</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][ac]" id="ac_no_${index}" value="Non-AC">
                            <label class="form-check-label" for="ac_no_${index}">🔥 Non-AC</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label d-block">🚿 Attach Bathroom</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][attach_bath]" id="bath_yes_${index}" value="Yes" checked>
                            <label class="form-check-label" for="bath_yes_${index}">✅ Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][attach_bath]" id="bath_no_${index}" value="No">
                            <label class="form-check-label" for="bath_no_${index}">❌ No</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-block">🚽 Toilet Type</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][toilet_type]" id="toilet_western_${index}" value="Western" checked>
                            <label class="form-check-label" for="toilet_western_${index}">Western</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rooms[${index}][toilet_type]" id="toilet_indian_${index}" value="Indian">
                            <label class="form-check-label" for="toilet_indian_${index}">Indian</label>
                        </div>
                    </div>
                </div>
            `;
            roomDetails.appendChild(block);
        });

        saveBtn.disabled = roomArray.length === 0;
    }
</script>

<!-- SweetAlert for messages -->
@if (session('room_error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Duplicate Room Numbers',
        text: '{{ session('room_error') }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

</body>
</html>
@endsection
