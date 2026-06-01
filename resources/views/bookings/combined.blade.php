@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Combined Bookings (Family + Group)</h3>

    <!-- FILTER FORM -->
    <form method="GET" action="{{ route('bookings.combined') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or phone">
        </div>
        <div class="col-md-2">
            <input type="text" name="aadhar_number" value="{{ request('aadhar_number') }}" class="form-control" placeholder="Aadhar">
        </div>
        <div class="col-md-2">
            <input type="text" name="city" value="{{ request('city') }}" class="form-control" placeholder="City">
        </div>
        <div class="col-md-2">
            <select name="aanchal" class="form-control">
                <option value="">-- Aanchal --</option>
                @foreach($aanchals as $a)
                    <option value="{{ $a->id }}" @if(request('aanchal') == $a->id) selected @endif>{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" name="id" value="{{ request('id') }}" class="form-control" placeholder="ID (+100)">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Aadhar</th>
                    <th>City</th>
                    <th>Aanchal</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Veer Parivar</th>
                    <th>Rooms</th>
                    <th>Hotel</th>
                    <th>Room Nos</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                    <tr>
                        <td>{{ ucfirst($b->type) }}</td>
                        <td>{{ $b->display_id }}</td>
                        <td>{{ $b->name }}</td>
                        <td>{{ $b->phone }}</td>
                        <td>{{ $b->aadhar_number }}</td>
                        <td>{{ $b->city_name ?? 'N/A' }}</td>
                        <td>{{ $b->aanchal_name ?? 'N/A' }}</td>
                        <td>{{ $b->check_in_date ?? '-' }}</td>
                        <td>{{ $b->check_out_date ?? '-' }}</td>
                        <td>{{ $b->status ?? '-' }}</td>
                        <td>{{ $b->is_veer_parivar ? 'Yes' : 'No' }}</td>
                        <td>{{ $b->rooms_allotted ? 'Yes' : 'No' }}</td>
                        <td>{{ $b->hotel_name ?? '-' }}</td>
                        <td>{{ is_array($b->room_numbers) ? implode(', ', $b->room_numbers) : '-' }}</td>
                        <td>
                            <!-- example action: view (adapt route as you have) -->
                            @if($b->type === 'family')
                                <a href="{{ route('family-booking.index', $b->original_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            @else
                                <a href="{{ route('group.booking.create', $b->original_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            @endif  
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="15" class="text-center">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center">
        {{ $bookings->appends(request()->query())->links() }}
    </div>
</div>
@endsection
