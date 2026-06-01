@extends('admin.layout')
@section('content')

<style>
/* Button Styles */
.btn-dark-outline {
    border: 1px solid #ccc;
    color: #444;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}
.btn-dark-outline:hover {
    background-color: #e2e6ea;
    color: #000;
    border-color: #888;
}
.btn-view { color: #fff; border-color: #007bff; background-color: #007bff; }
.btn-view:hover { background-color: #0056b3; border-color: #004085; }
.btn-members { color: #fff; border-color: #28a745; background-color: #28a745; }
.btn-members:hover { background-color: #218838; border-color: #1e7e34; }
.btn-edit { color: #000; border-color: #ffc107; background-color: #ffc107; }
.btn-edit:hover { background-color: #e0a800; border-color: #d39e00; }
.btn-danger { color: #fff; border-color: #dc3545; background-color: #dc3545; }
.btn-danger:hover { background-color: #c82333; border-color: #bd2130; }
.btn-solid { color: #212529; border-color: #007bff; background-color: rgb(221, 158, 31); }
.btn-solid:hover { background-color: #0056b3; border-color: rgb(193, 146, 29); }
</style>
<!-- Family Booking List Header -->
<div class="container mt-4 p-3 rounded shadow-lg bg-warning text-dark text-center" style="max-width: 450px; margin: 0 auto;">
    <h2 class="mb-0 font-weight-bold" style="line-height: 1.2;">📋 Family Booking List</h2>
    </div>

<!-- Export Button -->
<div class="container my-3 d-flex justify-content-end">
    <a href="{{ route('family-booking.export') }}" class="btn btn-success btn-sm">
        📥 Export to Excel
    </a>
</div>

<!-- Filter Box -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
     <form method="GET" action="{{ route('family-booking.index') }}" class="row g-3">

    {{-- Existing Filters --}}

     {{-- ✅ New Filter: ID input (will be processed -100 in controller) --}}
    <div class="col-md-3">
        <label for="id" class="form-label">🆔 Booking ID </label>
        <input type="number" name="id" id="id" class="form-control"
            value="{{ request('id') }}" placeholder="e.g. 110 (will check for 10)">
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

    <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <input type="text" name="status" id="status" class="form-control"
            value="{{ request('status') }}" placeholder="e.g. Pending or completed">
    </div>

    <div class="col-md-3">
        <label for="is_veer_parivar" class="form-label">Veer Pariwar</label>
        <select name="is_veer_parivar" id="is_veer_parivar" class="form-select">
            <option value="">-- Select --</option>
            <option value="1" {{ request('is_veer_parivar') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ request('is_veer_parivar') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

   
    <div class="col-md-6 d-flex justify-content-between align-items-end mt-2">
        <button type="submit" class="btn btn-primary w-50 me-2">🔎 Filter</button>
        <a href="{{ route('family-booking.index') }}" class="btn btn-secondary w-50">↺ Reset</a>
    </div>

</form>

    </div>
</div>

@if(session('success'))
    <script> Swal.fire("Success!", "{{ session('success') }}", "success"); </script>
@endif

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-light align-middle text-center">
        <thead class="table-dark sticky-top">
            <tr>
                <th>Sr-No</th>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Father's Name</th>
                <th>Phone</th>
                <th>City</th>
                <th>State</th>
                <th>Total Members</th>
                <th>Travel Type</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Family Coming</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $booking)
                <tr>
                    <td>{{ $bookings->firstItem() + $index }}</td>
                    <td>{{ $booking->id + 100 }}</td>
                    <td>{{ $booking->name }}</td>
                    <td>{{ $booking->father_name }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td>{{ $booking->cityName->city_name ?? 'N/A' }}</td>
                    <td>{{ $booking->stateName->state_name ?? 'N/A' }}</td>
                    <td>{{ $booking->total_persons }}</td>
                    <td>{{ $booking->travel_type }}</td>
                    <td>{{ $booking->check_in_date }}<br><small>{{ $booking->check_in_time }}</small></td>
                    <td>{{ $booking->check_out_date }}<br><small>{{ $booking->check_out_time }}</small></td>
                    <td>{{ $booking->family_coming == 1 ? 'Yes' : 'No' }}</td>
                    <td>
                        <div class="d-flex justify-content-center flex-wrap gap-1">
                            <button class="btn btn-sm btn-view btn-dark-outline view-more" data-booking='@json($booking)'>👁 View</button>

                            <button class="btn btn-sm btn-members btn-dark-outline view-members" data-booking-id="{{ $booking->id }}">👨‍👩‍👧‍👦 Members</button>

                       @if ($booking->status == 'completed')
    <!-- View Allotted Room Modal Trigger -->
    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewBookingModal{{ $booking->id }}">
        🛏 View Booking
    </button>

    <!-- Edit Button -->


    <!-- Check Out Button -->
    <form action="{{ route('family-booking.checkout', ['id' => $booking->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to check out this booking?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-warning btn-dark-outline">
            🔓 Check Out
        </button>
    </form>
@else
    <!-- Allot Room Form -->
    <form action="{{ route('alot.room') }}" method="GET" class="d-inline">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="total_persons" value="{{ $booking->total_persons }}">
        <input type="hidden" name="booking_type" value="family">
        <button type="submit" class="btn btn-sm btn-primary btn-solid">➕ Allot Room</button>
    </form>
@endif


                             <!-- Delete -->
                            <form action="{{ route('family-booking.destroy', ['id' => $booking->id]) }}" method="POST" class="d-inline" id="deleteForm{{ $booking->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-dark-outline" onclick="confirmDelete({{ $booking->id }})">
                                    🗑 Delete
                                </button>
                            </form>
                            <a href="{{ route('family-booking.edit', $booking->id) }}" class="btn btn-sm btn-info btn-dark-outline">
    ✏️ Edit
</a>

                        </div>
                    </td>
                </tr>

                <!-- Room Allotment Modal -->
                <div class="modal fade" id="viewBookingModal{{ $booking->id }}" tabindex="-1" aria-labelledby="viewBookingLabel{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content bg-dark text-light">
                            <div class="modal-header">
                                <h5 class="modal-title">Room Allotment Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @php
                                    $allotments = \App\Models\BookedRoom::where('booking_id', $booking->id)
                                        ->where('booking_type', 'family')->get();
                                @endphp
                                @if($allotments->count())
                                    @foreach($allotments as $allot)
                                        <p><strong>Hotel:</strong> {{ $allot->hotel->hotel_name ?? 'N/A' }}</p>
                                        <p><strong>Room Number:</strong> {{ $allot->room_number }}</p>
                                        <p><strong>Total Capacity:</strong> {{ $allot->total_capacity }}</p>
                                        <hr>
                                    @endforeach
                                @else
                                    <p>No room allotment details found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

   <div class="d-flex justify-content-center mt-3">
    {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

<!-- View More Modal -->
<div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Family Booking Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewMoreContent"></div>
        </div>
    </div>
</div>

<!-- Members Modal -->
<div class="modal fade" id="viewMembersModal" tabindex="-1" aria-labelledby="viewMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Family Members</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush" id="membersList"></ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // View More Modal Logic
    document.querySelectorAll(".view-more").forEach(button => {
        button.addEventListener("click", function () {
            const booking = JSON.parse(this.dataset.booking);
            const html = `
                <strong>Name:</strong> ${booking.name}<br>
                <strong>Father's Name:</strong> ${booking.father_name}<br>
                <strong>Phone:</strong> ${booking.phone}<br>
                <strong>Aadhar:</strong> ${booking.aadhar_number}<br>
                <strong>City:</strong> ${booking.city}<br>
                <strong>State:</strong> ${booking.state}<br>
                <strong>Aanchal:</strong> ${booking.aanchal}<br>
                <strong>Total Members:</strong> ${booking.total_persons}<br>
                <strong>Travel Type:</strong> ${booking.travel_type}<br>
                <strong>Check-in:</strong> ${booking.check_in_date} (${booking.check_in_time})<br>
                <strong>Check-out:</strong> ${booking.check_out_date} (${booking.check_out_time})<br>
                <strong>Coming:</strong> ${booking.no_of_people}<br>
                <strong>Male/Female:</strong> ${booking.total_male} / ${booking.total_female}<br>
                <strong>60+ Members:</strong> ${booking.sixty_plus_members}
            `;
            document.getElementById("viewMoreContent").innerHTML = html;
            new bootstrap.Modal(document.getElementById("viewMoreModal")).show();
        });
    });

    // View Members Modal Logic
    document.querySelectorAll(".view-members").forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.bookingId;
            fetch(`/get-family-members/${id}`)
                .then(res => res.json())
                .then(data => {
                    const membersList = document.getElementById("membersList");
                    membersList.innerHTML = data.length
                        ? data.map(m => `
                            <li class="list-group-item bg-dark text-light border-secondary">
                                <strong>Name:</strong> ${m.name} |
                                <strong>Father:</strong> ${m.father_name} |
                                <strong>Phone:</strong> ${m.mobile} |
                                <strong>Aadhar:</strong> ${m.aadhar_number}
                            </li>`).join('')
                        : `<li class="list-group-item bg-dark text-light border-secondary">No members found.</li>`;
                    new bootstrap.Modal(document.getElementById("viewMembersModal")).show();
                });
        });
    });
});

// Delete confirmation using SweetAlert
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This record will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`deleteForm${id}`);
            if (form) {
                form.submit();
            } else {
                console.error("Delete form not found for ID:", id);
            }
        }
    });
}
</script>


@endsection
