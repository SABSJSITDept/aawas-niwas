@extends('admin.layout')

@section('content')

<div class="container mt-5">
    <h3 class="text-center text-primary mb-4">
        📋 Group Bookings List
    </h3>
<a href="{{ route('export.group.bookings') }}" class="btn btn-success">Download Group Bookings Excel</a>


    <!-- Filter Box -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('group.booking.create') }}" class="row g-3">
            
<div class="col-md-3">
    <label for="booking_id" class="form-label">🧾 Search by Booking ID</label>
    <input type="number" name="booking_id" id="booking_id" class="form-control"
        value="{{ request('booking_id') }}" placeholder="e.g. 110123">
</div>


            <div class="col-md-3">
                <label for="search" class="form-label">🔍 Search Name / Phone</label>
                <input type="text" name="search" id="search" class="form-control"
                    value="{{ request('search') }}" placeholder="e.g. Aditya / 98765...">
            </div>

            <div class="col-md-3">
                <label for="aadhar_number" class="form-label">🆔 Aadhar Card</label>
                <input type="text" name="aadhar_number" id="aadhar_number" class="form-control"
                    value="{{ request('aadhar_number') }}" placeholder="e.g. xxxxxxxxxxxx">
            </div>

            <div class="col-md-3">
                <label for="city" class="form-label">🏙 City</label>
                <input type="text" name="city" id="city" class="form-control"
                    value="{{ request('city') }}" placeholder="e.g. Bikaner">
            </div>

    <div class="col-md-3">
    <label for="aanchal" class="form-label">🏙 Aanchal</label>
    <select name="aanchal" id="aanchal" class="form-control">
        <option value="">Select Aanchal</option>
        @foreach($aanchals as $aanchal)
            <option value="{{ $aanchal->anchal_id }}" {{ request('aanchal') == $aanchal->anchal_id ? 'selected' : '' }}>
                {{ $aanchal->name }}
            </option>
        @endforeach
    </select>
</div>



            <div class="col-md-3">
                <label for="travel_type" class="form-label">🚌 Travel Type</label>
                <select name="travel_type" id="travel_type" class="form-select">
                    <option value="">-- All --</option>
                    <option value="bus" {{ request('travel_type') == 'bus' ? 'selected' : '' }}>Bus</option>
                    <option value="train" {{ request('travel_type') == 'train' ? 'selected' : '' }}>Train</option>
                    <option value="car" {{ request('travel_type') == 'car' ? 'selected' : '' }}>Car</option>
                    <option value="flight" {{ request('travel_type') == 'flight' ? 'selected' : '' }}>Flight</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="check_in_date" class="form-label">📅 Check-in Date</label>
                <input type="date" name="check_in_date" id="check_in_date" class="form-control"
                    value="{{ request('check_in_date') }}">
            </div>

            <div class="col-md-3">
                <label for="check_out_date" class="form-label">📅 Check-out Date</label>
                <input type="date" name="check_out_date" id="check_out_date" class="form-control"
                    value="{{ request('check_out_date') }}">
            </div>

            <div class="col-md-6 d-flex justify-content-between align-items-end mt-2">
                <button type="submit" class="btn btn-primary w-50 me-2">🔎 Filter</button>
                <a href="{{ route('group.booking.create') }}" class="btn btn-secondary w-50">↺ Reset</a>
            </div>

        </form>
    </div>
</div>



    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm">
          <thead class="table-dark text-center">
    <tr>
        <th>Sr No</th>
        <th>Booking ID</th>
        <th>Name</th>
        <th>Father Name</th>
        <th>Phone</th>
        <th>City</th>
        <th>Aanchal</th>
        <th>Travel Type</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>People</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @php $sr = ($bookings->currentPage() - 1) * $bookings->perPage() + 1; @endphp
    @forelse($bookings as $booking)
        <tr class="text-center">
            <td>{{ $sr++ }}</td>
            <td>{{ $booking->id + 100 }}</td>
            <td>{{ $booking->name }}</td>
            <td>{{ $booking->father_name }}</td>
            <td>{{ $booking->phone }}</td>
            <td>{{ $booking->city_name }}</td> <!-- Display City Name -->
            <td>{{ $booking->aanchal_name }}</td> <!-- Display Aanchal Name -->
            <td>{{ $booking->travel_type }}</td>
            <td>{{ $booking->check_in_date }} {{ $booking->check_in_time }}</td>
            <td>{{ $booking->check_out_date }} {{ $booking->check_out_time }}</td>
            <td>{{ $booking->total_persons }}</td>
            <td>
                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                <a href="{{ route('group.booking.edit', $booking->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    ✏️ Edit
                                </a>

                                <!-- View Members -->
                                <button class="btn btn-info btn-sm view-members-btn" 
                                        data-id="{{ $booking->id }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#membersModal" 
                                        title="View Group Members">
                                    👥 Members
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('group.booking.destroy', $booking->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Booking">
                                        🗑️ Delete
                                    </button>
                                </form>

                              @if($booking->status == 'completed' && $booking->bookedRooms->count())
    <!-- Show Booking -->
    <button class="btn btn-primary btn-sm show-booking-btn"
            data-bs-toggle="modal"
            data-bs-target="#bookingModal"
            data-hotel="{{ $booking->hotel->hotel_name ?? 'N/A' }}"
            data-rooms="{{ $booking->bookedRooms->pluck('room_number')->join(', ') }}">
        🏨 Show Booking
    </button>

    <!-- Check Out -->
    <form action="{{ route('group-booking.checkout', ['id' => $booking->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to check out this booking?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-warning btn-sm">
            🔓 Check Out
        </button>
    </form>
@else
    <!-- Allot Room -->
    <form action="{{ route('alot.room') }}" method="GET" class="d-inline">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="total_persons" value="{{ $booking->total_persons }}">
        <input type="hidden" name="booking_type" value="group">
        <button type="submit" class="btn btn-success btn-sm" title="Allot Room">
            ✅ Allot Room
        </button>
    </form>
@endif
                            </div>
                        </td>
                    </tr>
                 @empty
        <tr>
            <td colspan="12" class="text-center">No bookings found</td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
</div>

<!-- Members Modal -->
<div class="modal fade" id="membersModal" tabindex="-1" aria-labelledby="membersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Group Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="membersModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Hotel Name:</strong> <span id="modalHotelName"></span></p>
                <p><strong>Room Numbers:</strong> <span id="modalRoomNumbers"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show booking modal
        document.querySelectorAll('.show-booking-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalHotelName').innerText = this.dataset.hotel;
                document.getElementById('modalRoomNumbers').innerText = this.dataset.rooms;
            });
        });

        // Load members in modal
        document.querySelectorAll('.view-members-btn').forEach(button => {
            button.addEventListener('click', function () {
                const bookingId = this.dataset.id;
                const modalBody = document.getElementById('membersModalBody');
                modalBody.innerHTML = `<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>`;

                fetch(`/admin/group-booking/${bookingId}/members`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            modalBody.innerHTML = `<p class="text-center text-muted">No members found.</p>`;
                            return;
                        }

                        let html = `<table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr><th>Name</th><th>Father Name</th><th>Mobile</th><th>Aadhar</th></tr>
                            </thead><tbody>`;
                        data.forEach(member => {
                            html += `<tr>
                                <td>${member.name}</td>
                                <td>${member.father_name}</td>
                                <td>${member.mobile_number}</td>
                                <td>${member.aadhar_number}</td>
                            </tr>`;
                        });
                        html += `</tbody></table>`;
                        modalBody.innerHTML = html;
                    })
                    .catch(() => {
                        modalBody.innerHTML = `<p class="text-danger text-center">Failed to load members.</p>`;
                    });
            });
        });
    });
</script>
@endsection
