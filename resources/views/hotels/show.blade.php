@extends('admin.layout')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<div class="container py-4">
    <div class="hotel-details card shadow-lg border-0 rounded-3 position-relative overflow-hidden animate__animated animate__fadeIn">
        <div class="hotel-header p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 text-white mb-0">{{ $hotel->hotel_name }}</h2>
                    <p class="text-white-50 mb-0"><i class="fas fa-map-marker-alt me-2"></i>Hotel Details</p>
                </div>
                <div class="hotel-actions">
                    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="stats-container px-4 pb-4">
            <div class="row g-4 stats-row animate__animated animate__fadeInUp">
                <div class="col-md-4">
                    <div class="stat-card bg-white rounded-3 p-3 shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary-subtle rounded-circle p-3 me-3">
                                <i class="fas fa-bed text-primary fs-4"></i>
                            </div>
                            <div>
                                @php
                                    $totalRooms = 0;
                                    foreach($hotel->roomFeatures->groupBy('category_id') as $rooms) {
                                        $totalRooms += $rooms->count();
                                    }
                                @endphp
                                <h3 class="fw-bold mb-0">{{ $totalRooms }}</h3>
                                <p class="text-muted mb-0">Total Rooms</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="categories-section px-4">
           
            
            <div class="row g-4">
                @if($hotel->roomCategories && $hotel->roomCategories->count() > 0)
                    @php
                        // Group by category_id + total_capacity (composite key)
                        $grouped = $hotel->roomCategories->groupBy(function($row) {
                            return $row->category_id . '_' . $row->total_capacity;
                        });
                    @endphp

                @foreach($grouped as $groupKey => $rows)
                    @php
                        $firstRow     = $rows->first();
                        $categoryName = $firstRow->category->category_name ?? 'No Category';
                        $capacity     = $firstRow->total_capacity;
                        $beds         = $firstRow->beds;
                        $extra        = $firstRow->extra_capacity;

                        // Merge all room numbers from all rows in this group
                        $allRooms = [];
                        foreach ($rows as $row) {
                            $nums = array_filter(array_map('trim', explode(',', $row->room_number)));
                            $allRooms = array_merge($allRooms, $nums);
                        }
                        $allRooms = array_values(array_unique($allRooms));
                    @endphp

                  <div class="col-lg-4 col-md-6 animate__animated animate__fadeInUp">
    <div class="category-card card shadow-hover h-100">
        <div class="card-header bg-light py-3 border-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0">{{ $categoryName }}</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary rounded-pill">{{ count($allRooms) }} Rooms</span>
                    <span class="badge bg-success rounded-pill">
                        <i class="fas fa-user me-1"></i>{{ $capacity }} / Room
                    </span>
                </div>
            </div>
            <div class="mt-2 text-muted small">
                <i class="fas fa-bed me-1"></i>Beds: {{ $beds }}
                @if($extra > 0)
                    &nbsp;+&nbsp;<i class="fas fa-plus-circle me-1"></i>Extra: {{ $extra }}
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- All Room Numbers -->
            <div class="category-info mb-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="feature-icon bg-warning-subtle rounded-circle p-2 me-2">
                        <i class="fas fa-door-open text-warning"></i>
                    </div>
                    <span class="text-muted fw-semibold">Room Numbers</span>
                </div>
                <div class="room-number-container">
                    @forelse($allRooms as $room)
                        <span class="room-badge" data-bs-toggle="tooltip" title="Room {{ $room }}">
                            {{ $room }}
                        </span>
                    @empty
                        <span class="text-muted fst-italic">No rooms assigned</span>
                    @endforelse
                </div>
            </div>

            <!-- Per-row action buttons (each DB entry has its own Edit/Delete) -->
            <div class="category-actions border-top pt-3">
                @foreach($rows as $row)
                    @php
                        $rowRooms = array_filter(array_map('trim', explode(',', $row->room_number)));
                    @endphp
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <small class="text-muted">
                            <i class="fas fa-layer-group me-1"></i>{{ $row->floor ?? '-' }}
                            &nbsp;|&nbsp;Rooms: {{ implode(', ', $rowRooms) }}
                        </small>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('room-category.edit', $row->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('room-features.edit', [$row->category_id, $hotel->id]) }}"
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-cog me-1"></i>Features
                            </a>
                            <form action="{{ route('room-category.delete', $row->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this entry? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

                @endforeach
            @else
                <p class="text-center text-muted">No Room Categories Available</p>
            @endif
        </div>
    </div>
</div>

<style>
    .hotel-header {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        margin: -1px -1px 0 -1px;
    }

    .hotel-details {
        background: #ffffff;
    }

    .stats-row {
        margin-top: -30px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.08);
    }

    .category-card:hover {
        transform: translateY(-5px);
    }

    .shadow-hover {
        transition: box-shadow 0.3s ease;
    }

    .shadow-hover:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }

    .feature-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .room-number-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .room-badge {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 36px;
        height: 36px;
        font-size: 14px;
        font-weight: 600;
        background-color: #f8f9fa;
        color: #2c3e50;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .room-badge:hover {
        background-color: #4CAF50;
        color: white;
        border-color: #4CAF50;
        transform: scale(1.05);
    }

    .category-actions {
        border-top: 1px solid rgba(0,0,0,0.08);
        padding-top: 1rem;
    }

    .action-buttons .btn {
        transition: all 0.2s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
    }

    .animate__animated {
        animation-duration: 0.8s;
    }

    @media (max-width: 768px) {
        .stats-row {
            margin-top: 0;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add staggered animation delay to category cards
    document.querySelectorAll('.animate__fadeInUp').forEach((element, index) => {
        element.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection
