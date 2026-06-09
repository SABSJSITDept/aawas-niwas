@extends('admin.layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <h3 class="mb-1 fw-bold text-dark">Allotment Report</h3>
                    <p class="mb-0 text-muted small">View and export allotment details grouped by booking with smart filters.</p>
                </div>
                <div class="text-md-end">
                    <span class="badge bg-primary rounded-pill px-3 py-2">Total Bookings: {{ collect($report)->groupBy('booking_id')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="card-body bg-light pb-4">
            <div class="bg-white rounded-4 shadow-sm p-4 mb-4 border">
                <form method="GET" class="row g-3">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Hotel Name</label>
                        <select name="hotel_name" class="form-select form-select-lg">
                            <option value="">All Hotels</option>
                            @foreach(\App\Models\HotelDetails::where('status', 'active')->get() as $hotel)
                                <option value="{{ $hotel->hotel_name }}" {{ ($filters['hotel_name'] ?? '') == $hotel->hotel_name ? 'selected' : '' }}>
                                    {{ $hotel->hotel_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Room Number</label>
                        <input type="text" name="room_number" value="{{ $filters['room_number'] ?? '' }}" class="form-control form-control-lg" placeholder="Room no.">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Booked By</label>
                        <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control form-control-lg" placeholder="Guest name">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Check-in Date</label>
                        <input type="date" name="check_in_date" value="{{ $filters['check_in_date'] ?? '' }}" class="form-control form-control-lg">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Check-out Date</label>
                        <input type="date" name="check_out_date" value="{{ $filters['check_out_date'] ?? '' }}" class="form-control form-control-lg">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label text-secondary small mb-2">Entries per page</label>
                        <select name="per_page" class="form-select form-select-lg">
                            <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ ($filters['per_page'] ?? 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ ($filters['per_page'] ?? 10) == 100 ? 'selected' : '' }}>100</option>
                            <option value="150" {{ ($filters['per_page'] ?? 10) == 150 ? 'selected' : '' }}>150</option>
                            <option value="200" {{ ($filters['per_page'] ?? 10) == 200 ? 'selected' : '' }}>200</option>
                            <option value="all" {{ ($filters['per_page'] ?? 10) == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex flex-column flex-sm-row gap-2 justify-content-end mt-2">
                        <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center shadow-sm">
                            <i class="bi bi-search me-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.room.report') }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center shadow-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mb-3">
                <div class="text-muted small">Use filters to narrow bookings by hotel, room or dates.</div>
                <a href="{{ route('admin.room.report.export', request()->query()) }}" class="btn btn-success btn-lg shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Export to Excel
                </a>
            </div>

            <div class="table-responsive shadow-sm rounded-4 overflow-hidden border bg-white">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small text-secondary">Booking ID</th>
                            <th class="text-uppercase small text-secondary">Booked By</th>
                            <th class="text-uppercase small text-secondary">Phone</th>
                            <th class="text-uppercase small text-secondary">Total Persons</th>
                            <th class="text-uppercase small text-secondary">Room Numbers</th>
                            <th class="text-uppercase small text-secondary">Hotel Name</th>
                            <th class="text-uppercase small text-secondary">Check-in</th>
                            <th class="text-uppercase small text-secondary">Check-out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grouped = collect($report)->groupBy('booking_id');
                        @endphp
                        @forelse($grouped as $bookingId => $rooms)
                            <tr>
                                <td class="fw-semibold">{{ $bookingId }}</td>
                                <td>{{ $rooms->first()['name'] }}</td>
                                <td>{{ $rooms->first()['phone'] ?? '-' }}</td>
                                <td>{{ $rooms->first()['total_persons'] ?? '-' }}</td>
                                <td>{{ implode(', ', $rooms->pluck('room_number')->toArray()) }}</td>
                                <td>{{ $rooms->first()['hotel_name'] }}</td>
                                <td>
                                    @if (!empty($rooms->first()['check_in_date']) && $rooms->first()['check_in_date'] !== '-')
                                        {{ \Carbon\Carbon::parse($rooms->first()['check_in_date'])->format('d M Y') }}
                                        @if (!empty($rooms->first()['check_in_time']) && $rooms->first()['check_in_time'] !== '-')
                                            <div class="text-muted small mt-1">{{ $rooms->first()['check_in_time'] }}</div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($rooms->first()['check_out_date']) && $rooms->first()['check_out_date'] !== '-')
                                        {{ \Carbon\Carbon::parse($rooms->first()['check_out_date'])->format('d M Y') }}
                                        @if (!empty($rooms->first()['check_out_time']) && $rooms->first()['check_out_time'] !== '-')
                                            <div class="text-muted small mt-1">{{ $rooms->first()['check_out_time'] }}</div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger py-4">❌ कोई डेटा नहीं मिला</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                @if($bookedRooms instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $bookedRooms->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('styles')
<style>
    .form-label {
        letter-spacing: 0.02em;
        text-transform: uppercase;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .btn-primary,
    .btn-success,
    .btn-outline-secondary {
        border-radius: 12px;
        min-height: 48px;
    }

    .table th,
    .table td {
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }

    .table thead th {
        border-bottom: 2px solid #dee2e6;
    }

    .table-responsive {
        background-color: #ffffff;
    }

    .card-header h3 {
        letter-spacing: 0.01em;
    }

    @media (max-width: 575.98px) {
        .form-label {
            font-size: 0.72rem;
        }
    }
</style>
@endpush
@endsection
