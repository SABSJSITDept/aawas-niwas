@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4 text-primary">🚌 परिवहन रिपोर्ट – Hotel Stay (By Date)</h3>

<form method="GET" action="{{ route('admin.parivahan.datewise.report') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="date" class="form-label">📅 Select Date:</label>
        <input type="date" id="date" name="date" class="form-control"
               value="{{ $selectedDate ?? now()->toDateString() }}">
    </div>
    <div class="col-md-3">
        <label for="report_time" class="form-label">⏰ Select Time:</label>
        <input type="time" id="report_time" name="report_time" class="form-control" value="{{ request('report_time', now()->format('H:i')) }}">
    </div>
    <div class="col-md-4">
        <label for="hotel_name" class="form-label">🏨 Select Hotel:</label>
        <select id="hotel_name" name="hotel_name" class="form-select">
            <option value="">All Hotels</option>
            @if(isset($hotels))
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->hotel_name }}" {{ request('hotel_name') == $hotel->hotel_name ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-primary w-100">🔍 Show</button>
    </div>
    <div class="col-md-2 align-self-end">
        <a href="{{ route('admin.parivahan.datewise.report.pdf', array_merge(request()->query(), ['date' => $selectedDate])) }}" class="btn btn-danger w-100">
            🧾 Download PDF
        </a>
    </div>
</form>


    <div class="table-responsive rounded-4 shadow-sm border bg-white mt-4">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th class="text-uppercase small text-secondary">Sr. No.</th>
                    <th class="text-uppercase small text-secondary">Hotel Name</th>
                    <th class="text-uppercase small text-secondary">Date</th>
                    <th class="text-uppercase small text-secondary">Total Staying</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $i => $row)
                    <tr>
                        <td class="fw-semibold">{{ $i + 1 }}</td>
                        <td>{{ $row['hotel_name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                        <td>{{ $row['total_staying'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-danger py-4">❌ No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }
    .table thead th {
        border-bottom: 2px solid #dee2e6;
        background: #f0f6ff;
        font-size: 0.92rem;
        letter-spacing: 0.03em;
    }
    .table-responsive {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .fw-semibold {
        font-weight: 600;
    }
    @media (max-width: 575.98px) {
        .table thead th {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@endsection
