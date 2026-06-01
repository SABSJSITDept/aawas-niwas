@extends('admin.layout')

@section('content')
<div class="container my-5">

    <!-- Hero / Header -->
    <div class="bg-white rounded-4 p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between">
        <div>
            <h3 class="mb-1 fw-bold">📋 Room Booking Report</h3>
            <div class="text-muted">Filters, summary and export for hotel room bookings.</div>
        </div>
        <div>
           
            @if(!empty($reportData))
                <a href="{{ route('admin.room.booking.report.pdf', request()->only(['hotel_id','check_in_date'])) }}" class="btn btn-success">📥 Download PDF</a>
            @endif
        </div>
    </div>

    <!-- Filters (Hotel Name + Check-in Date) -->
    <form method="GET" action="{{ route('admin.room.booking.report') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-6">
            <label class="form-label">Hotel Name</label>
            <select name="hotel_id" class="form-select">
                <option value="">All Hotels</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Check-in Date</label>
            <input type="date" name="check_in_date" class="form-control" value="{{ request('check_in_date') }}" required>
        </div>

        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">🔍</button>
        </div>
    </form>

    <!-- Summary Cards -->
    @php
        $totalBookings = !empty($reportData) ? count($reportData) : 0;
        $totalPersons = !empty($reportData) ? collect($reportData)->sum('total_persons') : 0;
        $uniqueRooms = !empty($reportData) ? collect($reportData)->pluck('room_number')->unique()->count() : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3 display-6 text-primary">📦</div>
                    <div>
                        <div class="small text-muted">Total Bookings</div>
                        <div class="fw-bold fs-5">{{ $totalBookings }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3 display-6 text-success">👥</div>
                    <div>
                        <div class="small text-muted">Total Persons</div>
                        <div class="fw-bold fs-5">{{ $totalPersons }}</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Report Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(!empty($reportData))
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hotel</th>
                                <th>Room</th>
                                <th>Booked By</th>
                                <th>Phone</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Persons</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $data)
                                <tr>
                                    <td>{{ $data['hotel_name'] }}</td>
                                    <td>{{ $data['room_number'] }}</td>
                                    <td>{{ $data['booked_by'] }}</td>
                                    <td>{{ $data['phone'] ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data['check_in_date'])->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data['check_out_date'])->format('d M Y') }}</td>
                                    <td>{{ $data['total_persons'] }}</td>
                                    <td>
                                      
                                        <a href="tel:{{ $data['phone'] ?? '' }}" class="btn btn-sm btn-outline-secondary">Call</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4"></i>
                    <div class="mt-3">No data found for the selected filters.</div>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .display-6 { font-size: 2rem; }
    .card p.small { margin-bottom: 0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        // simple UX: disable submit briefly to show loading
        const btn = this.querySelector('button[type=submit]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Searching...';
            setTimeout(() => { btn.disabled = false; btn.innerHTML = '🔍'; }, 3000);
        }
    });
});
</script>

@endsection
