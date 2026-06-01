@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-4 fw-bold">
        🛠️ Edit Room Features - {{ $category->category_name }} | {{ $hotel->hotel_name }}
    </h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('room-features.update') }}">
        @csrf

        <table class="table table-bordered">
            <thead class="table-light text-center align-middle">
                <tr>
                    <th>Room Number</th>
                    <th>AC</th>
                    <th>Attached Bath</th>
                    <th>Toilet Type</th>
                    <th>Status</th> {{-- ✅ New Column --}}
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                <tr class="align-middle text-center">
                    <input type="hidden" name="features[{{ $feature->id }}][id]" value="{{ $feature->id }}">

                    <td>{{ $feature->room_number }}</td>

                    <td>
                        <select name="features[{{ $feature->id }}][ac]" class="form-control">
                            <option value="AC" {{ $feature->ac == 'AC' ? 'selected' : '' }}>AC</option>
                            <option value="Non-AC" {{ $feature->ac == 'Non-AC' ? 'selected' : '' }}>Non-AC</option>
                        </select>
                    </td>

                    <td>
                        <select name="features[{{ $feature->id }}][attach_bath]" class="form-control">
                            <option value="Yes" {{ $feature->attach_bath == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $feature->attach_bath == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </td>

                    <td>
                        <select name="features[{{ $feature->id }}][toilet_type]" class="form-control">
                            <option value="Western" {{ $feature->toilet_type == 'Western' ? 'selected' : '' }}>Western</option>
                            <option value="Indian" {{ $feature->toilet_type == 'Indian' ? 'selected' : '' }}>Indian</option>
                        </select>
                    </td>

                    <td>
                        <select name="features[{{ $feature->id }}][status]" class="form-control">
                            <option value="active" {{ $feature->status == 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="inactive" {{ $feature->status == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-success">💾 Update Features</button>
    </form>
</div>
@endsection
