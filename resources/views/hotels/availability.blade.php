@extends('admin.layout')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css"/>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="hotel-header card shadow-sm border-0 rounded-3 mb-4 animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="display-6 text-white mb-1">Room Availability</h2>
                    <p class="text-white-50 mb-0">
                        <i class="fas fa-building me-2"></i>{{ $hotel->hotel_name }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <a href="{{ route('rooms.export', ['hotel_id' => $hotel->id]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-2"></i>Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card shadow-sm border-0 rounded-3 mb-4 animate__animated animate__fadeIn">
        <div class="card-body">
            <h6 class="mb-3 fw-bold">Room Status Guide:</h6>
            <div class="d-flex flex-wrap gap-4">
                <div class="d-flex align-items-center">
                    <div style="width: 16px; height: 16px; border-radius: 50%; background: #28a745; margin-right: 8px;"></div>
                    <span>Available</span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 16px; height: 16px; border-radius: 50%; background: #ffc107; margin-right: 8px;"></div>
                    <span>Partially Occupied</span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 16px; height: 16px; border-radius: 50%; background: #dc3545; margin-right: 8px;"></div>
                    <span>Fully Occupied</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card shadow-sm border-0 rounded-3 mb-4 animate__animated animate__fadeIn">
        <div class="card-body">
            <form method="GET" class="d-flex gap-3 align-items-end flex-wrap">
                <div>
                    <label for="from_date" class="form-label fw-bold">From Date</label>
                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date', now()->toDateString()) }}" required>
                </div>
                <div>
                    <label for="to_date" class="form-label fw-bold">To Date</label>
                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', now()->addDay()->toDateString()) }}" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filter Availability
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Room Categories -->
    @php
        // Group categories by category name + capacity so all rooms of same type are shown together
        $groupedCategories = $categories->groupBy(function($cat) {
            $name = $cat->category->category_name ?? 'No Name';
            $cap  = $cat->total_capacity ?? 0;
            return $name . '||' . $cap;
        });

        $fromDate = request('from_date', now()->toDateString());
        $toDate = request('to_date', now()->addDay()->toDateString());
    @endphp

    @foreach ($groupedCategories as $groupKey => $groupCats)
        @php
            [$groupCategoryName, $groupCapacity] = explode('||', $groupKey, 2);
        @endphp

        <div class="card shadow-hover border-0 rounded-3 mb-4 animate__animated animate__fadeInUp">
            <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #e8eaf6 0%, #f3f4f8 100%);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">
                            <i class="bi bi-building me-2" style="color:#1a237e;"></i>{{ $groupCategoryName }}
                        </h4>
                        <div class="d-flex gap-3">
                            <span class="badge rounded-pill px-3 py-2" style="background:#1a237e; font-size:13px;">
                                <i class="bi bi-people-fill me-1"></i>{{ $groupCapacity }} persons / room
                            </span>
                            <span class="badge rounded-pill px-3 py-2 bg-secondary" style="font-size:13px;">
                                <i class="bi bi-door-open me-1"></i>
                                {{ $groupCats->sum(fn($c) => count(array_filter(array_map('trim', explode(',', $c->room_number))))) }} rooms
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="room-grid">
                    @foreach ($groupCats as $category)
                        @php
                            $roomNumbers = explode(',', $category->room_number);
                        @endphp

                        @foreach ($roomNumbers as $room)
                            @php
                                $room = trim($room);
                                if (empty($room)) continue;

                                // Check if room is active
                                $roomFeatures = \App\Models\RoomFeatures::where('hotel_id', $category->hotel_id)
                                    ->where('room_number', $room)
                                    ->where('status', 'active')
                                    ->first();

                                if (!$roomFeatures) continue;

                                // Get room capacity directly from current category (avoids FIND_IN_SET space issue)
                                $roomCapacity = $category->total_capacity ?? 0;

                                // Get bookings that overlap with the selected date range
                                $bookings = \App\Models\BookedRoom::where('hotel_id', $category->hotel_id)
                                    ->where('room_number', $room)
                                    ->where(function($q) use ($fromDate, $toDate) {
                                        $q->where('check_in_date', '<=', $toDate)
                                          ->where('check_out_date', '>=', $fromDate);
                                    })
                                    ->with(['familyBooking', 'groupBooking'])
                                    ->get();

                                // Calculate booked capacity by summing all overlapping bookings
                                $booked = $bookings->sum('total_capacity');
                                $available = $roomCapacity - $booked;

                                // Determine status based on availability
                                $status = $available <= 0 ? 'full' : ($available < $roomCapacity ? 'partial' : 'empty');

                                // Build tooltip
                                $tooltip = '';
                                if ($bookings->count() > 0) {
                                    foreach ($bookings as $booking) {
                                        $name = 'N/A';
                                        $phone = 'N/A';
                                        $checkIn = 'N/A';
                                        $checkOut = 'N/A';
                                        $type = 'Booking';
                                        $found = false;

                                        if ($booking->familyBooking) {
                                            $name = $booking->familyBooking->name ?? 'N/A';
                                            $phone = $booking->familyBooking->phone ?? 'N/A';
                                            $checkIn = $booking->familyBooking->check_in_date ? date('d-m-Y', strtotime($booking->familyBooking->check_in_date)) : 'N/A';
                                            $checkOut = $booking->familyBooking->check_out_date ? date('d-m-Y', strtotime($booking->familyBooking->check_out_date)) : 'N/A';
                                            $type = 'Family';
                                            $found = true;
                                        } elseif ($booking->groupBooking) {
                                            $name = $booking->groupBooking->group_head ?? 'N/A';
                                            $phone = $booking->groupBooking->group_phone ?? 'N/A';
                                            $checkIn = $booking->groupBooking->check_in_date ? date('d-m-Y', strtotime($booking->groupBooking->check_in_date)) : 'N/A';
                                            $checkOut = $booking->groupBooking->check_out_date ? date('d-m-Y', strtotime($booking->groupBooking->check_out_date)) : 'N/A';
                                            $type = 'Group';
                                            $found = true;
                                        }

                                        if ($found) {
                                            $tooltip .= "<div style='padding: 10px; background: #f8f9fa; border-radius: 6px; margin-bottom: 8px; border-left: 4px solid #0d47a1;'>
                                                <strong style='color: #0d47a1; font-size: 13px;'>$type Booking</strong><br>
                                                <small style='color: #555; font-size: 12px; line-height: 1.6;'>
                                                    <strong>Name:</strong> $name<br>
                                                    <strong>Phone:</strong> $phone<br>
                                                    <strong>Check-in:</strong> $checkIn<br>
                                                    <strong>Check-out:</strong> $checkOut<br>
                                                    <strong>Persons:</strong> " . $booking->total_capacity . "
                                                </small>
                                            </div>";
                                        }
                                    }
                                }
                            @endphp

                            @php
                                $hasTooltip = !empty($tooltip);
                            @endphp
                            <div class="room-item"
                                 data-room="{{ $room }}"
                                 data-status="{{ $status }}"
                                 data-has-tooltip="{{ $hasTooltip ? 'true' : 'false' }}">
                                <div class="room-box room-{{ $status }}"
                                     @if($hasTooltip)
                                     data-tippy-content="{!! htmlspecialchars($tooltip, ENT_QUOTES, 'UTF-8') !!}"
                                     @endif>
                                    <div class="room-icon"><i class="bi {{ $status === 'full' ? 'bi-door-closed' : 'bi-door-open' }}"></i></div>
                                    <div class="room-number">{{ $room }}</div>
                                    <div class="room-capacity">{{ $available }}/{{ $roomCapacity }}</div>
                                    @if ($bookings->count() > 0)
                                        <div class="info-badge"><i class="bi bi-info-circle"></i></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
    .hotel-header {
        background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
    }

    .hotel-header .text-white {
        color: white !important;
    }

    .hotel-header .text-white-50 {
        color: rgba(255,255,255,0.7) !important;
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 24px;
        padding: 20px 0;
    }

    .room-item {
        cursor: pointer;
    }

    .room-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px;
        border-radius: 16px;
        transition: all 0.3s ease;
        text-align: center;
        min-height: 140px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .room-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .room-empty {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
    }

    .room-partial {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #1a1a1a;
    }

    .room-full {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .room-icon {
        font-size: 32px;
    }

    .room-number {
        font-size: 20px;
        font-weight: 700;
    }

    .room-capacity {
        font-size: 14px;
        font-weight: 600;
        opacity: 0.85;
    }

    .info-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .shadow-hover {
        transition: all 0.3s ease;
    }

    .shadow-hover:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .tippy-box {
        background-color: white;
        color: #333;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .room-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 16px;
        }

        .room-box {
            padding: 16px;
            gap: 10px;
            min-height: 120px;
        }

        .room-number {
            font-size: 18px;
        }

        .room-icon {
            font-size: 24px;
        }
    }
</style>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Tippy to load
    if (typeof tippy === 'undefined') {
        console.error('❌ Tippy.js not loaded');
        return;
    }
    
    console.log('✓ Tippy.js loaded successfully');
    
    // Initialize Tippy on all elements with data-tippy-content
    const roomBoxes = document.querySelectorAll('.room-box[data-tippy-content]');
    console.log(`Found ${roomBoxes.length} room boxes with tooltips`);
    
    if (roomBoxes.length > 0) {
        tippy(roomBoxes, {
            allowHTML: true,
            interactive: true,
            placement: 'right',
            theme: 'light-border',
            maxWidth: 400,
            arrow: true,
            delay: [100, 0],
            animation: 'scale',
            duration: [200, 150],
            trigger: 'mouseenter focus',
            onShow(instance) {
                console.log('Showing tooltip for:', instance.reference.closest('.room-item').getAttribute('data-room'));
            }
        });
        console.log('✓ Tippy initialized for all room boxes');
    } else {
        console.warn('⚠ No room boxes found with tooltip data');
    }
});
</script>

@endsection
