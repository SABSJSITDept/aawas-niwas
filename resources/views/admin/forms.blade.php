@extends('admin.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2 class="text-center mb-3 fw-bold text-primary">Forms Records</h2>

    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('forms.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Download Excel
        </a>
    </div>

    <div class="table-responsive shadow-sm p-1 bg-white rounded">
        <table class="table table-striped table-hover table-bordered align-middle text-center">
            <thead class="table-dark text-white">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Post</th>
                    <th>Comming</th>
                    <th>Accommodation</th>
                    <th>Check-In Date</th>
                    <th>Check-Out Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="table-light">
                @foreach($forms as $form)
                    <tr>
                        <td class="fw-bold">{{ $form->id }}</td>
                        <td>{{ $form->name }}</td>
                        <td>{{ $form->phone }}</td>
                        <td>{{ $form->department }}</td>
                        <td>{{ $form->post }}</td>
                        <td>{{ $form->is_coming == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $form->stay_arrangement }}</td>
                        <td>{{ $form->check_in_date }}</td>
                        <td>{{ $form->check_out_date }}</td>
                        <td>
    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $form->id }}">
    Show More
</button>

<form action="{{ route('forms.destroy', $form->id) }}" method="POST" class="d-inline-block">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
        Delete
    </button>
</form>

@if ($form->status == 'completed' && $form->bookedRooms && $form->bookedRooms->count())
    <button class="btn btn-primary btn-sm show-booking-btn"
        data-bs-toggle="modal"
        data-bs-target="#bookingModal"
        data-hotel="{{ $form->hotel->hotel_name ?? 'N/A' }}"
        data-rooms="{{ $form->bookedRooms->pluck('room_number')->join(', ') }}">
        🏨 Show Booking
    </button>
@elseif ($form->is_coming == 1 && strtoupper(trim($form->stay_arrangement)) == 'SANGH KI VYAVASTA')
    <form action="{{ route('alot.room') }}" method="GET" class="d-inline">
        <input type="hidden" name="booking_id" value="{{ $form->id }}">
        <input type="hidden" name="total_persons" value="1">
        <input type="hidden" name="booking_type" value="vip">
        <button type="submit" class="btn btn-success btn-sm" title="Allot Room">
            ✅ Allot Room
        </button>
    </form>
@endif



{{-- ✅ Checkout Button – only show when status is completed --}}
@if ($form->status == 'completed')
    <form action="{{ route('vip.checkout', $form->id) }}" method="POST" class="d-inline-block">
        @csrf
        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to checkout?')">
            🔁 Check-out
        </button>
    </form>
@endif



                    <!-- Modal for Showing More Details -->
                    <div class="modal fade" id="detailsModal{{ $form->id }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fw-bold">Details of {{ $form->name }}</h5>
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>ID:</strong> {{ $form->id }}</p>
                                            <p><strong>Phone:</strong> {{ $form->phone }}</p>
                                            <p><strong>Aadhar:</strong> {{ $form->aadhar_number }}</p>
                                            <p><strong>M-ID:</strong> {{ $form->mid }}</p>
                                            <p><strong>City:</strong> {{ $form->city }}</p>
                                            <p><strong>State:</strong> {{ $form->state }}</p>
                                            <p><strong>Aanchal:</strong> {{ $form->aanchal }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Department:</strong> {{ $form->department }}</p>
                                            <p><strong>Post:</strong> {{ $form->post }}</p>
                                            <p><strong>Coming:</strong> {{ $form->is_coming ? 'Yes' : 'No' }}</p>
                                            <p><strong>Accommodation:</strong> {{ $form->stay_arrangement }}</p>
                                            <p><strong>Travel Type:</strong> {{ $form->travel_type }}</p>
                                            <p><strong>Check-In:</strong> {{ $form->check_in_date }} at {{ $form->check_in_time }}</p>
                                            <p><strong>Check-Out:</strong> {{ $form->check_out_date }} at {{ $form->check_out_time }}</p>
                                            <p><strong>Created At:</strong> {{ $form->created_at->format('d M Y, h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-warning">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Hotel:</strong> <span id="modalHotel"></span></p>
        <p><strong>Rooms:</strong> <span id="modalRooms"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $forms->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var bookingModal = document.getElementById('bookingModal');
    bookingModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var hotelName = button.getAttribute('data-hotel');
        var rooms = button.getAttribute('data-rooms');

        document.getElementById('modalHotel').textContent = hotelName;
        document.getElementById('modalRooms').textContent = rooms;
    });
});
</script>

@endsection
