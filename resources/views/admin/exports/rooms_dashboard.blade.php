@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">Hotel Availability Dashboard</h4>
        <div>
            <a href="{{ route('rooms.export.all', request()->query()) }}" class="btn btn-success me-2">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('rooms.export.all.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('rooms.dashboard') }}">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Select Hotel</label>
                        <select name="hotel_id" class="form-select">
                            <option value="">All Hotels</option>
                            @foreach(\App\Models\HotelDetails::where('status', 'active')->get() as $h)
                                <option value="{{ $h->id }}" {{ request('hotel_id') == $h->id ? 'selected' : '' }}>{{ $h->hotel_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label fw-bold">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date', now()->toDateString()) }}">
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label fw-bold">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date', now()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                        <a href="{{ route('rooms.dashboard') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overall Summary Card -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0">Overall Summary</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <h3 class="text-primary">{{ $totals['total_rooms'] }}</h3>
                    <p class="text-muted">Total Rooms</p>
                </div>
                <div class="col-md-3">
                    <h3 class="text-info">{{ $totals['total_capacity'] }}</h3>
                    <p class="text-muted">Total Capacity</p>
                </div>
                <div class="col-md-3">
                    <h3 class="text-danger">{{ $totals['total_booked'] }}</h3>
                    <p class="text-muted">Total Booked</p>
                </div>
                <div class="col-md-3">
                    <h3 class="text-success">{{ $totals['total_available'] }}</h3>
                    <p class="text-muted">Total Available</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Details -->
    @foreach($data as $hotel)
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0">{{ $hotel['hotel_name'] }}</h5>
            <span class="badge bg-light text-dark">
                Available: {{ $hotel['totals']['total_available'] }} / {{ $hotel['totals']['total_capacity'] }}
            </span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Room No</th>
                        <th>Category</th>
                        <th>Floor</th>
                        <th>Beds</th>
                        <th>Extra Cap.</th>
                        <th>Total Cap.</th>
                        <th>Booked</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hotel['rooms'] as $room)
                    <tr>
                        <td>{{ $room['room_no'] }}</td>
                        <td>{{ $room['category'] }}</td>
                        <td>{{ $room['floor'] }}</td>
                        <td>{{ $room['beds'] }}</td>
                        <td>{{ $room['extra_capacity'] }}</td>
                        <td>{{ $room['total_capacity'] }}</td>
                        <td class="{{ $room['booked'] > 0 ? 'text-danger fw-bold' : '' }}">{{ $room['booked'] }}</td>
                        <td class="{{ $room['available'] > 0 ? 'text-success fw-bold' : 'text-muted' }}">{{ $room['available'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">Hotel Totals:</td>
                        <td>{{ $hotel['totals']['total_capacity'] }}</td>
                        <td class="text-danger">{{ $hotel['totals']['total_booked'] }}</td>
                        <td class="text-success">{{ $hotel['totals']['total_available'] }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endforeach

</div>
@endsection
