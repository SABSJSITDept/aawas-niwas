@extends('admin.layout')
@section('content')

<div class="container py-4">
    <h3 class="mb-3 fw-bold text-primary">📅 Date-wise Check-in, Check-out & Stay Report</h3>

    <form method="GET" action="{{ route('admin.checkin.report') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-success me-2">🔍 Filter</button>
            <a href="{{ route('admin.checkin.report.pdf', request()->query()) }}" class="btn btn-danger">⬇️ Download PDF</a>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SR NO.</th>
                <th>Date</th>
                <th>Check-ins</th>
                <th>Check-outs</th>
                <th>🧍‍♂️ Staying</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report as $index => $entry)
                <tr>
                    <td>{{ ($report->currentPage() - 1) * $report->perPage() + $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                    <td>{{ $entry['checkin'] }}</td>
                    <td>{{ $entry['checkout'] }}</td>
                    <td>{{ $entry['staying'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $report->links() }}
    </div>
</div>

@endsection
