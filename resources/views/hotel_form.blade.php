@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="my-2">🏨 Add Hotel & Room Categories</h3>
                </div>
                <div class="card-body">
                    <form action="{{ url('/add-hotel') }}" method="POST">
                        @csrf

                        {{-- Hotel Details Section --}}
                        <h5 class="text-secondary mb-3">🏠 Hotel Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Hotel Name</label>
                                <input type="text" name="hotel_name" class="form-control" required placeholder="Enter hotel name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Rooms</label>
                                <input type="number" name="total_rooms" class="form-control" required placeholder="Total rooms">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Available Rooms</label>
                                <input type="number" name="available_rooms" class="form-control" required placeholder="Available rooms">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        {{-- Room Categories Section --}}
                        <div class="border-top mt-4 pt-4">
                            <h5 class="text-secondary mb-3">🛏 Room Categories</h5>
                            <div id="roomCategories">
                                <div class="room-category row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" name="categories[0][name]" class="form-control" placeholder="Category Name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="categories[0][total]" class="form-control" placeholder="Total Rooms" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="categories[0][room_numbers]" class="form-control" placeholder="Room Numbers (comma separated)" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addCategory" class="btn btn-outline-primary btn-sm mt-2">+ Add Category</button>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-success w-50">✅ Submit Hotel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Adding More Categories --}}
<script>
    document.getElementById('addCategory').addEventListener('click', function() {
        let index = document.querySelectorAll('.room-category').length;
        let newCategory = `
            <div class="room-category row mb-3">
                <div class="col-md-4">
                    <input type="text" name="categories[${index}][name]" class="form-control" placeholder="Category Name" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="categories[${index}][total]" class="form-control" placeholder="Total Rooms" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="categories[${index}][room_numbers]" class="form-control" placeholder="Room Numbers (comma separated)" required>
                </div>
            </div>
        `;
        document.getElementById('roomCategories').insertAdjacentHTML('beforeend', newCategory);
    });
</script>
@endsection
