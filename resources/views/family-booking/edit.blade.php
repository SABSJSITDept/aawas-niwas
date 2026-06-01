@extends('admin.layout')

@section('content')
<div class="container">
    <h2 class="my-3">✏️ Edit Family Booking</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editBookingForm" action="{{ route('family-booking.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 👤 Personal Details -->
        <div class="card mb-3 p-3 shadow-sm">
            <h5>👤 Personal Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $booking->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Father Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $booking->father_name) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age', $booking->age) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $booking->phone) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Aadhar Number</label>
                    <input type="text" name="aadhar_number" class="form-control" value="{{ old('aadhar_number', $booking->aadhar_number) }}">
                </div>
            </div>
        </div>

        <!-- 🛡️ Veer Parivar -->
        <div class="card mb-3 p-3 shadow-sm">
            <h5>🛡️ Veer Parivar Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Is Veer Parivar?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_veer_parivar" value="1" {{ $booking->is_veer_parivar ? 'checked' : '' }} onclick="toggleVeerFields(true)"> Yes
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_veer_parivar" value="0" {{ !$booking->is_veer_parivar ? 'checked' : '' }} onclick="toggleVeerFields(false)"> No
                    </div>
                </div>
                <div class="col-md-6 mb-3 veer-fields" style="display: none;">
                    <label>Veer Relation</label>
                    <input type="text" name="veer_relation" class="form-control" value="{{ old('veer_relation', $booking->veer_relation) }}">
                </div>
                <div class="col-md-6 mb-3 veer-fields" style="display: none;">
                    <label>MS Name</label>
                    <input type="text" name="ms_name" class="form-control" value="{{ old('ms_name', $booking->ms_name) }}">
                </div>
            </div>
        </div>

        <!-- 👨‍👩‍👧‍👦 Family Details -->
        <div class="card mb-3 p-3 shadow-sm">
            <h5>👨‍👩‍👧‍👦 Family Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Family Coming?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="family_coming" value="1" {{ $booking->family_coming ? 'checked' : '' }} onclick="toggleFamilyFields(true)"> Yes
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="family_coming" value="0" {{ !$booking->family_coming ? 'checked' : '' }} onclick="toggleFamilyFields(false)"> No
                    </div>
                </div>

<div class="row family-fields" style="display: none;">
    <div class="col-md-3 mb-3">
        <label>No. of People</label>
<input type="number" id="noOfPeople" name="no_of_people" class="form-control"
    value="{{ old('no_of_people', $booking->no_of_people) }}"
    oninput="limitPeopleInput(this)" min="0">

    </div>
     <div class="col-md-3 mb-3">
    <label>Total Persons</label>
    <input type="number" id="totalPersons" name="total_persons" class="form-control bg-light" readonly>
</div>
    <div class="col-md-3 mb-3">
        <label>No. of Children</label>
        <input type="number" name="no_of_children" class="form-control"
            value="{{ old('no_of_children', $booking->no_of_children) }}">
    </div>
    <div class="col-md-3 mb-3">
        <label>Total Male</label>
        <input type="number" name="total_male" class="form-control"
            value="{{ old('total_male', $booking->total_male) }}">
    </div>
    <div class="col-md-3 mb-3">
        <label>Total Female</label>
        <input type="number" name="total_female" class="form-control"
            value="{{ old('total_female', $booking->total_female) }}">
    </div>

    <!-- ✅ Total Persons (Readonly) -->
   
</div>

                </div>
            </div>
        </div>

        <!-- 👵 60+ Members -->
        <div class="card mb-4 p-3 shadow-sm">
            <h5>👵 60+ Members</h5>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="sixtyPlusCheck" onclick="toggleSixtyFields(this.checked)">
                <label class="form-check-label" for="sixtyPlusCheck">Include 60+ Members</label>
            </div>
            <div class="row sixty-fields" style="display: none;">
                <div class="col-md-3 mb-3">
                    <label>60+ Male</label>
                    <input type="number" name="sixty_plus_male" class="form-control" value="{{ old('sixty_plus_male', $booking->sixty_plus_male) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>60+ Female</label>
                    <input type="number" name="sixty_plus_female" class="form-control" value="{{ old('sixty_plus_female', $booking->sixty_plus_female) }}">
                </div>
            </div>
        </div>

        <!-- 🧳 Travel & Stay (Now Last) -->
        <div class="card mb-5 p-3 shadow-sm">
            <h5>🧳 Travel & Stay</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Travel Type</label>
                    <input type="text" name="travel_type" class="form-control" value="{{ old('travel_type', $booking->travel_type) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in_date" class="form-control" value="{{ old('check_in_date', $booking->check_in_date) }}">
                </div>
             <div class="col-md-3 mb-3">
    <label>Check-in Time</label>
    <input type="time" name="check_in_time" class="form-control"
        value="{{ old('check_in_time', substr($booking->check_in_time, 0, 5)) }}">
</div>

<div class="col-md-3 mb-3">
    <label>Check-out Date</label>
    <input type="date" name="check_out_date" class="form-control"
        value="{{ old('check_out_date', $booking->check_out_date) }}">
</div>

<div class="col-md-3 mb-3">
    <label>Check-out Time</label>
    <input type="time" name="check_out_time" class="form-control"
        value="{{ old('check_out_time', substr($booking->check_out_time, 0, 5)) }}">
</div>

            </div>
        </div>

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-success px-4 py-2">💾 Update Booking</button>
        </div>
    </form>
</div>

<script>
    function toggleVeerFields(show) {
        document.querySelectorAll('.veer-fields').forEach(el => {
            el.style.display = show ? 'block' : 'none';
        });
    }

   function toggleFamilyFields(show) {
    const fields = document.querySelectorAll('.family-fields');
    fields.forEach(el => {
        el.style.display = show ? 'flex' : 'none';
        if (!show) {
            // reset all input values
            el.querySelectorAll('input').forEach(input => input.value = '');
        }
    });
    if (!show) {
        document.getElementById('totalPersons').value = '';
    }
}


    function toggleSixtyFields(show) {
        document.querySelectorAll('.sixty-fields').forEach(el => {
            el.style.display = show ? 'flex' : 'none';
        });
    }

function calculateTotalPersons() {
    let noOfPeople = parseInt(document.getElementById('noOfPeople').value || 0);
    let totalPersons = noOfPeople + 1; // include head
    document.getElementById('totalPersons').value = totalPersons;
}

function limitPeopleInput(input) {
    let value = parseInt(input.value) || 0;
    if (value > 10) {
        input.value = 10;
    } else if (value < 0) {
        input.value = 0;
    }
    calculateTotalPersons(); // in case you use totalPersons calculation
}


    document.addEventListener('DOMContentLoaded', function () {
        toggleVeerFields({{ $booking->is_veer_parivar ? 'true' : 'false' }});
        toggleFamilyFields({{ $booking->family_coming ? 'true' : 'false' }});
        let sixty = {{ $booking->sixty_plus_male ?? 0 }} + {{ $booking->sixty_plus_female ?? 0 }};
        document.getElementById('sixtyPlusCheck').checked = sixty > 0;
        toggleSixtyFields(sixty > 0);
        calculateTotalPersons();
    });
    
</script>
@endsection
