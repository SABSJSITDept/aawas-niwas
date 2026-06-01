@extends('admin.layout')
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>⚠️ {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">📝 Edit Group Booking</h4>
        </div>
        <div class="card-body px-4 py-4">
            <form action="{{ route('group.booking.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Section: Personal Details --}}
                <h5 class="text-secondary border-bottom pb-2 mb-4">👤 Personal Details</h5>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">👨 Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $booking->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">👴 Father's Name</label>
                        <input type="text" name="father_name" class="form-control" value="{{ $booking->father_name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">📞 Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $booking->phone }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">🆔 Aadhar Number</label>
                        <input type="text" name="aadhar_number" class="form-control" value="{{ $booking->aadhar_number }}" required>
                    </div>
                </div>

                {{-- Section: Members Details --}}
                <h5 class="text-secondary border-bottom pb-2 mb-4">👪 Members Details</h5>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">👥 Total Members (excluding head)</label>
                        <input type="number" name="total_members" class="form-control" value="{{ $booking->total_members }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">👤 Total Persons</label>
                        <input type="number" name="total_persons" class="form-control" value="{{ $booking->total_persons }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">🙋‍♂️ Total Male</label>
                        <input type="number" name="total_male" class="form-control" value="{{ $booking->total_male }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">🙋‍♀️ Total Female</label>
                        <input type="number" name="total_female" class="form-control" value="{{ $booking->total_female }}" required>
                    </div>
                  <div class="col-md-4">
    <label class="form-label">🧓 60+ Members</label>
    <select name="sixty_plus_members" class="form-select" id="sixty_plus_members" required>
        <option value="1" {{ $booking->sixty_plus_members == 1 ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ $booking->sixty_plus_members == 0 ? 'selected' : '' }}>No</option>
    </select>
</div>

                    <div class="col-md-4 sixty-fields">
                        <label class="form-label">👴 60+ Male</label>
                        <input type="number" name="sixty_plus_male" class="form-control" value="{{ $booking->sixty_plus_male }}">
                    </div>
                    <div class="col-md-4 sixty-fields">
                        <label class="form-label">👵 60+ Female</label>
                        <input type="number" name="sixty_plus_female" class="form-control" value="{{ $booking->sixty_plus_female }}">
                    </div>
                </div>

                {{-- Section: Booking Details --}}
                <h5 class="text-secondary border-bottom pb-2 mb-4">🚌 Booking & Travel Details</h5>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">🚖 Travel Type</label>
                        <select name="travel_type" class="form-select" required>
                            <option value="Train" {{ $booking->travel_type == 'Train' ? 'selected' : '' }}>Train</option>
                            <option value="Bus" {{ $booking->travel_type == 'Bus' ? 'selected' : '' }}>Bus</option>
                            <option value="Car" {{ $booking->travel_type == 'Car' ? 'selected' : '' }}>Car</option>
                        </select>
                    </div>
                   
                    <div class="col-md-6">
                        <label class="form-label">📅 Check-in Date</label>
                        <input type="date" name="check_in_date" class="form-control" value="{{ $booking->check_in_date }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">📅 Check-out Date</label>
                        <input type="date" name="check_out_date" class="form-control" value="{{ $booking->check_out_date }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">⏰ Check-in Time</label>
                        <input type="time" name="check_in_time" class="form-control" value="{{ $booking->check_in_time }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">⏰ Check-out Time</label>
                        <input type="time" name="check_out_time" class="form-control" value="{{ $booking->check_out_time }}" required>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-5 py-2">💾 Update Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS for dynamic behavior --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const membersInput = document.querySelector('input[name="total_members"]');
    const totalPersonsInput = document.querySelector('input[name="total_persons"]');
    const sixtyPlusSelect = document.getElementById('sixty_plus_members');
    const sixtyFields = document.querySelectorAll('.sixty-fields');

    // Auto-calculate total_persons = total_members + 1
    membersInput.addEventListener('input', function () {
        const members = parseInt(this.value) || 0;
        totalPersonsInput.value = members + 1;
    });

    // Show/hide 60+ fields
    function toggleSixtyFields() {
        const isYes = sixtyPlusSelect.value === 'Yes';
        sixtyFields.forEach(field => {
            field.style.display = isYes ? 'block' : 'none';
            const input = field.querySelector('input');
            if (!isYes) {
                input.value = ''; // Clear value if No
            }
        });
    }

    sixtyPlusSelect.addEventListener('change', toggleSixtyFields);
    toggleSixtyFields(); // On page load
});
</script>

<style>
    .sixty-fields {
        display: none;
    }
</style>
@endsection
