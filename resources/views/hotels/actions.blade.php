@extends('admin.layout')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .hotel-header {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        padding: 40px 0;
        margin-bottom: 30px;
        border-radius: 0 0 40px 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .hotel-title {
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .hotel-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.2rem;
    }

    .action-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        height: 100%;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: #4CAF50;
    }

    .action-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        margin: 0 auto 20px;
        font-size: 32px;
        transition: all 0.3s ease;
    }

    .view-rooms .action-icon {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .add-rooms .action-icon {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .check-availability .action-icon {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .action-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .action-description {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 25px;
    }

    .action-button {
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }


</style>

<div class="hotel-header animate__animated animate__fadeIn">
    <div class="container">
        <div class="text-center">
            <h1 class="hotel-title mb-2">{{ $hotel->hotel_name }}</h1>
            <p class="hotel-subtitle mb-0">Hotel Management Dashboard</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- View Rooms -->
        <div class="col-md-4 animate__animated animate__fadeInUp">
            <div class="action-card view-rooms">
                <div class="action-icon">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <h3 class="action-title text-center">View Rooms</h3>
                <p class="action-description text-center">
                    Comprehensive overview of all rooms, their status, and occupancy details.
                </p>
                <div class="text-center">
                    <a href="{{ route('hotel.show', $hotel->id) }}" 
                       class="action-button btn btn-primary">
                        <i class="bi bi-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>

        <!-- Add Rooms -->
        <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="action-card add-rooms">
                <div class="action-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <h3 class="action-title text-center">Add Rooms</h3>
                <p class="action-description text-center">
                    Add new rooms with detailed specifications and category assignments.
                </p>
                <div class="text-center">
                    <a href="{{ route('room.create', $hotel->id) }}" 
                       class="action-button btn btn-success">
                        <i class="bi bi-plus-lg me-2"></i>Add New
                    </a>
                </div>
            </div>
        </div>

        <!-- Check Availability -->
        <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="action-card check-availability">
                <div class="action-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="action-title text-center">Check Availability</h3>
                <p class="action-description text-center">
                    Real-time overview of room availability and booking status.
                </p>
                <div class="text-center">
                    <a href="{{ route('hotel.availability', $hotel->id) }}" 
                       class="action-button btn btn-info">
                        <i class="bi bi-search me-2"></i>Check Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hover effects

    // Add hover effect for action cards
    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.action-icon').style.transform = 'scale(1.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.querySelector('.action-icon').style.transform = 'scale(1)';
        });
    });
});
</script>
@endsection
