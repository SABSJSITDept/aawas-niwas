@extends('admin.layout')

@section('content')
<div class="container py-4">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Room Category
        </h3>
    </div>

    <!-- Info Message -->
    <div class="alert alert-info d-flex align-items-center shadow-sm rounded-3">
        <i class="bi bi-info-circle-fill fs-5 me-2"></i>
        <div>
            यदि आपको किसी room की capacity बदलनी है,  
            तो कृपया नीचे से उस room को delete करें और फिर से नई entry करें।
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form Card -->
    <div class="card shadow-lg border-0 rounded-4 mb-5">
        <div class="card-header bg-light fw-semibold py-3 rounded-top-4">
            <i class="bi bi-gear me-2"></i> Update Room Category Details
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('room-category.update', $roomCategory->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="hotel_id" value="{{ $roomCategory->hotel_id }}">

                <div class="row g-4">
                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select class="form-select shadow-sm rounded-3" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $roomCategory->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Floor -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Floor</label>
                        <input type="text" name="floor" class="form-control shadow-sm rounded-3"
                               value="{{ $roomCategory->floor }}" required>
                    </div>

                    <!-- Beds -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Beds</label>
                        <input type="number" name="beds" class="form-control shadow-sm rounded-3"
                               value="{{ $roomCategory->beds }}" required>
                    </div>

                    <!-- Extra Capacity -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Extra Capacity</label>
                        <input type="number" name="extra_capacity" class="form-control shadow-sm rounded-3"
                               value="{{ $roomCategory->extra_capacity }}">
                    </div>

                    <!-- Existing Room Numbers -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Existing Room Numbers</label>
                        <input type="text" class="form-control shadow-sm rounded-3 bg-light"
                               value="{{ $roomCategory->room_number }}" readonly>
                        <div class="form-text text-muted">
                            ये Room Numbers पहले से मौजूद हैं, इन्हें हटाया नहीं जा सकता।
                        </div>
                    </div>

                    <!-- Add New Room Numbers -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Add New Room Numbers (comma separated)</label>
                        <input type="text" name="new_room_numbers" id="new_room_numbers" class="form-control shadow-sm rounded-3"
                               placeholder="e.g. 7,8" value="{{ old('new_room_numbers') }}">
                        <div class="form-text text-muted">
                            केवल नए Room Numbers दर्ज करें।
                        </div>
                    </div>

                    <!-- Per-room features (dynamically generated) -->
                    <div class="col-md-12" id="new_room_features_section" style="display:none;">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-sliders me-2"></i>नए Rooms की Features</h6>
                        <div id="per_room_features"></div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-success shadow-sm rounded-3 py-2">
                        <i class="bi bi-save me-1"></i> Update Room Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Section Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-danger text-white fw-semibold py-3 rounded-top-4">
            <i class="bi bi-trash3-fill me-2"></i> Delete Individual Room Number
        </div>
        <div class="card-body p-4">
            <form action="{{ route('room-category.delete-room', $roomCategory->id) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Room Number(s) to Delete</label>
                        <input type="text" name="delete_room_number" class="form-control shadow-sm rounded-3" placeholder="e.g. 5 or 5,6,7" required>
                        <div class="form-text text-muted">एक या अधिक room numbers comma से अलग करके लिखें।</div>
                    </div>
                    <div class="col-md-6 d-grid">
                        <button class="btn btn-danger shadow-sm rounded-3">
                            <i class="bi bi-x-circle me-1"></i> Delete Room
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Bootstrap Icons (if not loaded already) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('new_room_numbers');
        const section = document.getElementById('new_room_features_section');
        const container = document.getElementById('per_room_features');

        function buildFeatures() {
            const raw = input.value.trim();
            const rooms = raw.split(',').map(r => r.trim()).filter(r => r !== '');

            if (rooms.length === 0) {
                section.style.display = 'none';
                container.innerHTML = '';
                return;
            }

            section.style.display = 'block';

            // Keep existing values when re-rendering (collect before clearing)
            const existing = {};
            container.querySelectorAll('.room-feature-row').forEach(row => {
                const rn = row.dataset.room;
                existing[rn] = {
                    ac:          row.querySelector('[data-field="ac"]').value,
                    attach_bath: row.querySelector('[data-field="attach_bath"]').value,
                    toilet_type: row.querySelector('[data-field="toilet_type"]').value,
                };
            });

            container.innerHTML = '';

            rooms.forEach(function (room) {
                const prev = existing[room] || {};
                const ac          = prev.ac          || 'Non-AC';
                const attach_bath = prev.attach_bath || 'No';
                const toilet_type = prev.toilet_type || 'Indian';

                container.insertAdjacentHTML('beforeend', `
                    <div class="room-feature-row card border border-primary-subtle rounded-3 p-3 mb-3 bg-light" data-room="${room}">
                        <div class="fw-semibold text-primary mb-2">
                            <i class="bi bi-door-open me-1"></i> Room ${room}
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">AC / Non-AC</label>
                                <select name="room_features[${room}][ac]" data-field="ac" class="form-select form-select-sm shadow-sm rounded-3">
                                    <option value="AC"     ${ac === 'AC'     ? 'selected' : ''}>AC</option>
                                    <option value="Non-AC" ${ac === 'Non-AC' ? 'selected' : ''}>Non-AC</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Attach Bath</label>
                                <select name="room_features[${room}][attach_bath]" data-field="attach_bath" class="form-select form-select-sm shadow-sm rounded-3">
                                    <option value="Yes" ${attach_bath === 'Yes' ? 'selected' : ''}>Yes</option>
                                    <option value="No"  ${attach_bath === 'No'  ? 'selected' : ''}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Toilet Type</label>
                                <select name="room_features[${room}][toilet_type]" data-field="toilet_type" class="form-select form-select-sm shadow-sm rounded-3">
                                    <option value="Indian"  ${toilet_type === 'Indian'  ? 'selected' : ''}>Indian</option>
                                    <option value="Western" ${toilet_type === 'Western' ? 'selected' : ''}>Western</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `);
            });
        }

        input.addEventListener('input', buildFeatures);
        buildFeatures(); // run on page load for old() repopulation
    });
</script>
@endsection
