@extends('admin.layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 0;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-title {
        color: white;
        margin: 0;
        font-size: 36px;
        font-weight: 700;
        text-align: center;
        z-index: 1;
        position: relative;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
        margin-top: 8px;
        font-size: 14px;
        font-weight: 500;
    }

    .booking-info-card {
        background: white;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f2f5;
    }

    .booking-detail {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 8px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .booking-detail:hover {
        background: linear-gradient(135deg, #f0f2f5 0%, #f5f7fa 100%);
        transform: translateX(4px);
    }

    .booking-detail i {
        margin-right: 12px;
        color: #667eea;
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .booking-detail strong {
        color: #764ba2;
        font-weight: 600;
    }

    .section-title {
        font-size: 20px;
        color: #2c3e50;
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #667eea;
        font-size: 28px;
    }

    .progress-wrapper {
        background: white;
        padding: 18px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
        border: 1px solid #f0f2f5;
        position: sticky;
        top: 80px;
        z-index: 1000;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .progress-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 16px;
    }

    .progress-counter {
        background: #667eea;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: width 0.5s ease;
        height: 100%;
    }
</style>

@php
    // --- Safety: prevent Closure values from reaching Blade echo (fixes htmlspecialchars() error)
    $safeScalars = [
        'booking_id','booking_type','total_persons',
        'checkInDate','checkOutDate','mobileNumber','selectedHotelId'
    ];
    foreach ($safeScalars as $varName) {
        if (isset($$varName) && ($$varName instanceof \Closure)) {
            $$varName = null;
        }
    }
@endphp
<div class="page-header animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="page-title">
            <i class="bi bi-door-open" style="margin-right: 12px; vertical-align: middle;"></i>Room Allotment
        </h1>
        <p class="page-subtitle">Select hotel and allocate rooms for your booking</p>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success animate__animated animate__fadeInDown" style="margin-top:20px; font-size:18px; border-radius: 12px; border-left: 4px solid #28a745;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Booking Information Card -->
    <div class="booking-info-card animate__animated animate__fadeInUp">
        <h2 class="section-title">
            <i class="bi bi-info-circle-fill"></i>Booking Details
        </h2>
        <div class="row">
            <div class="col-md-12">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <div class="booking-detail">
                        <i class="bi bi-person-fill"></i>
                        <div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Total Persons</div>
                            <strong>{{ $total_persons ?? 0 }} Members</strong>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <i class="bi bi-calendar-check-fill"></i>
                        <div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Check In</div>
                            <strong>{{ $checkInDate ? date('d M, Y', strtotime($checkInDate)) : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <i class="bi bi-calendar-x-fill"></i>
                        <div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Check Out</div>
                            <strong>{{ $checkOutDate ? date('d M, Y', strtotime($checkOutDate)) : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <i class="bi bi-phone-fill"></i>
                        <div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Contact</div>
                            <strong>{{ $mobileNumber ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="progress-wrapper animate__animated animate__fadeInUp">
        <div class="progress-header">
            <span class="progress-label"><i class="bi bi-bar-chart" style="margin-right: 8px;"></i>Allocation Progress</span>
            <span class="progress-counter"><span id="progressText">0</span>/{{ $total_persons ?? 0 }} persons</span>
        </div>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
        </div>
    </div>

    <!-- Room Status Legend -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin: 28px 0; background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #f0f2f5;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 20px; height: 20px; background: #28a745; border-radius: 50%; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);"></div>
            <div>
                <div style="font-size: 12px; color: #6c757d;">Available</div>
                <div style="font-size: 14px; font-weight: 600; color: #2c3e50;">Ready to allocate</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 20px; height: 20px; background: #ffc107; border-radius: 50%; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);"></div>
            <div>
                <div style="font-size: 12px; color: #6c757d;">Partially Booked</div>
                <div style="font-size: 14px; font-weight: 600; color: #2c3e50;">Space available</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 20px; height: 20px; background: #dc3545; border-radius: 50%; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);"></div>
            <div>
                <div style="font-size: 12px; color: #6c757d;">Fully Booked</div>
                <div style="font-size: 14px; font-weight: 600; color: #2c3e50;">Not available</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 20px; height: 20px; background: #667eea; border-radius: 50%; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);"></div>
            <div>
                <div style="font-size: 12px; color: #6c757d;">Selected</div>
                <div style="font-size: 14px; font-weight: 600; color: #2c3e50;">By you</div>
            </div>
        </div>
    </div>

    {{-- SweetAlert for Room Allotment Success --}}
    @if(session('popupData'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Room Allotted Successfully!',
                html: `<b>Hotel:</b> {{ session('popupData.hotel_name') }}<br><b>Room(s):</b> {{ session('popupData.room_numbers') }}`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#667eea',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function() {
                window.location.href = "{{ route('registration.list') }}";
            });
        });
        </script>
    @endif

    {{-- Booking Info (hidden) --}}
    <input type="hidden" id="booking_id" value="{{ $booking_id ?? '' }}">
    <input type="hidden" id="booking_type" value="{{ $booking_type ?? '' }}">
    <input type="hidden" id="total_persons" value="{{ $total_persons ?? 0 }}">
    <input type="hidden" name="check_in_date" value="{{ $checkInDate ?? '' }}">
    <input type="hidden" name="check_out_date" value="{{ $checkOutDate ?? '' }}">
    <input type="hidden" name="mobile_number" value="{{ $mobileNumber ?? '' }}">

    <!-- Hotel Selection -->
    <div class="animate__animated animate__fadeInUp" style="margin-bottom: 16px;" id="hotelSelectionSection">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <h2 class="section-title" style="margin: 0;">
                <i class="bi bi-building"></i>Step 1: Select Hotel
            </h2>
            <button type="button" id="closeHotelBtn" style="background: #e9ecef; border: none; padding: 8px 16px; border-radius: 12px; cursor: pointer; color: #6c757d; font-weight: 500; display: none; transition: all 0.3s ease; gap: 6px; align-items: center;">
                <i class="bi bi-x-lg" style="margin-right: 6px;"></i>Close Hotels
            </button>
        </div>
        <div class="hotel-selection-container" id="hotelListContainer">
            @foreach($hotels as $hotel)
                @php
                    $params = array_filter([
                        'booking_id' => $booking_id ?? null,
                        'booking_type' => $booking_type ?? null,
                        'hotel_id' => $hotel->id
                    ], function($v){ return !is_null($v) && $v !== ''; });
                    $href = route('alot.room', $params);
                @endphp

                <div class="hotel-card {{ ($selectedHotelId == $hotel->id) ? 'selected' : '' }}"
                     data-href="{{ $href }}"
                     data-hotel-id="{{ $hotel->id }}">
                    <div style="display: flex; align-items: center; gap: 18px; width: 100%;">
                        <div class="hotel-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div style="flex: 1;">
                            <div class="hotel-name">{{ $hotel->hotel_name }}</div>
                            <div style="font-size: 13px; color: #6c757d; margin-top: 4px;">
                                @if($selectedHotelId == $hotel->id)
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 20px; font-weight: 500;">
                                        <i class="bi bi-check-circle-fill"></i>Selected
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 6px; color: #667eea; font-weight: 500;">
                                        <i class="bi bi-arrow-right"></i>Click to select
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="bi bi-chevron-right" style="font-size: 20px; color: #667eea; opacity: 0.6;"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Rooms Display --}}
    @if(!empty($selectedHotelId) && count($categories))
        <div class="animate__animated animate__fadeInUp">
            <h2 class="section-title" style="margin-bottom: 14px;">
                <i class="bi bi-door-open"></i>Step 2: Select Rooms
            </h2>
            
            <!-- Selection Summary -->
            <div style="background: white; padding: 16px; border-radius: 16px; margin-bottom: 16px; border: 1px solid #f0f2f5; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div>
                    <div style="font-size: 12px; color: #6c757d; margin-bottom: 3px;">Allocation Summary</div>
                    <div style="font-size: 16px; font-weight: 700; color: #2c3e50;">
                        <span id="selectedCapacity">0</span> / <span>{{ $total_persons ?? 0 }}</span> persons allocated
                    </div>
                </div>
                <div style="background: #f0f2f5; padding: 10px 14px; border-radius: 12px; border-left: 4px solid #667eea;">
                    <div style="font-size: 11px; color: #6c757d; margin-bottom: 3px;">Still needed</div>
                    <div style="font-size: 16px; font-weight: 700; color: #764ba2;" id="capacityNeeded">{{ $total_persons ?? 0 }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('alot.room.store') }}" id="roomForm">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking_id ?? '' }}">
                <input type="hidden" name="booking_type" value="{{ $booking_type ?? '' }}">
                <input type="hidden" name="check_in_date" value="{{ $checkInDate ?? '' }}">
                <input type="hidden" name="check_out_date" value="{{ $checkOutDate ?? '' }}">
                <input type="hidden" name="mobile_number" value="{{ $mobileNumber ?? '' }}">
                <input type="hidden" name="rooms_json" id="rooms_json">

                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; width: 100%;" class="rooms-grid">
                @foreach ($categories as $category)
                    @php
                        $roomNumbers = explode(',', $category->room_number);
                    @endphp
                    @foreach ($roomNumbers as $room)
                        @php
                            $room = trim($room);
                            if ($room === '') continue;

                            $featureActive = \App\Models\RoomFeatures::where('hotel_id', $category->hotel_id)
                                ->where('room_number', $room)
                                ->where('status', 'active')
                                ->exists();

                            if (!$featureActive) continue;

                            $currentBookings = \App\Models\BookedRoom::where('hotel_id', $category->hotel_id)
                                        ->where('room_number', $room)
                                        ->where(function($query) use ($checkInDate, $checkOutDate) {
                                            $query->where(function($q) use ($checkInDate, $checkOutDate) {
                                                $q->where(function($inner) use ($checkInDate, $checkOutDate) {
                                                    $inner->whereDate('check_in_date', '>=', $checkInDate)
                                                        ->whereDate('check_in_date', '<=', $checkOutDate);
                                                })->orWhere(function($inner) use ($checkInDate, $checkOutDate) {
                                                    $inner->whereDate('check_out_date', '>=', $checkInDate)
                                                        ->whereDate('check_out_date', '<=', $checkOutDate);
                                                })->orWhere(function($inner) use ($checkInDate, $checkOutDate) {
                                                    $inner->whereDate('check_in_date', '<=', $checkInDate)
                                                        ->whereDate('check_out_date', '>=', $checkOutDate);
                                                });
                                            });
                                        })
                                        ->get();

                            $bookedCapacity = $currentBookings->sum('total_capacity');
                            $available = $category->total_capacity - $bookedCapacity;

                            $bookingDetails = [];
                            foreach($currentBookings as $booking) {
                                $familyBooking = \App\Models\FamilyBooking::where('booking_id', $booking->booking_id)
                                    ->orWhere('id', $booking->booking_id)
                                    ->first();
                                if ($familyBooking) {
                                    $bookingDetails[] = [
                                        'name' => $familyBooking->name,
                                        'phone' => $familyBooking->phone,
                                        'check_in' => $familyBooking->check_in_date,
                                        'check_out' => $familyBooking->check_out_date,
                                        'persons' => $booking->total_capacity,
                                        'type' => 'Family'
                                    ];
                                    continue;
                                }

                                $groupBooking = \App\Models\GroupBooking::where('booking_id', $booking->booking_id)
                                    ->orWhere('id', $booking->booking_id)
                                    ->first();
                                if ($groupBooking) {
                                    $bookingDetails[] = [
                                        'name' => $groupBooking->name,
                                        'phone' => $groupBooking->phone,
                                        'check_in' => $groupBooking->check_in_date,
                                        'check_out' => $groupBooking->check_out_date,
                                        'persons' => $booking->total_capacity,
                                        'type' => 'Group'
                                    ];
                                    continue;
                                }

                                $vipForm = \App\Models\Form::where('booking_id', $booking->booking_id)
                                    ->orWhere('id', $booking->booking_id)
                                    ->first();
                                if ($vipForm) {
                                    $bookingDetails[] = [
                                        'name' => $vipForm->name,
                                        'phone' => $vipForm->phone,
                                        'check_in' => $vipForm->check_in_date,
                                        'check_out' => $vipForm->check_out_date,
                                        'persons' => $booking->total_capacity,
                                        'type' => 'VIP'
                                    ];
                                }
                            }

                            $tooltipHtml = '';
                            if (count($bookingDetails) > 0) {
                                $tooltipHtml = '<div style="padding: 12px; max-width: 300px;">';
                                foreach ($bookingDetails as $detail) {
                                    $tooltipHtml .= sprintf(
                                        '<div style="padding: 10px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px; border-left: 3px solid #667eea;"><strong style="color: #2c3e50;">%s Booking</strong><br><small style="color: #6c757d;">%s<br>Ph: %s<br>Check-in: %s<br>Check-out: %s<br>Persons: %d</small></div>',
                                        $detail['type'],
                                        $detail['name'],
                                        $detail['phone'],
                                        date('d-m-Y', strtotime($detail['check_in'])),
                                        date('d-m-Y', strtotime($detail['check_out'])),
                                        $detail['persons']
                                    );
                                }
                                $tooltipHtml .= '</div>';
                            }
                            $status = $available <= 0 ? 'full' : ($available < $category->total_capacity ? 'partial' : 'empty');
                        @endphp

                        <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
                            <div style="font-size: 11px; color: #6c757d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; width: 100%;">
                                {{ $category->category->category_name ?? 'Room' }}
                            </div>
                            <div class="room-card {{ $status }} {{ $available <= 0 ? 'disabled' : '' }} animate__animated animate__fadeIn" 
                                 data-room="{{ $room }}"
                                 data-capacity="{{ $available }}"
                                 data-hotel="{{ $category->hotel_id }}"
                                 data-tooltip-html="{{ $tooltipHtml }}"
                                 style="position: relative; overflow: hidden; width:100%;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; gap: 2px; width: 100%;">
                                    <div style="font-size: 18px; opacity: 0.7; line-height: 1;">
                                        <i class="bi {{ $status === 'full' ? 'bi-door-closed' : 'bi-door-open' }}"></i>
                                    </div>
                                    <div style="font-size: 12px; font-weight: 700; color: inherit; text-align: center; line-height: 1;">{{ $room }}</div>
                                    <div style="font-size: 9px; opacity: 0.8; text-align: center; line-height: 1;">
                                        {{ $available }}/{{ $category->total_capacity }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
                </div>

                <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px;">
                    <button type="submit" id="allotButton" class="btn-primary-modern" disabled>
                        <i class="bi bi-check-circle" style="margin-right: 8px;"></i>Allot Selected Rooms
                    </button>
                </div>
            </form>
        </div>
    @else
        <div style="background: white; padding: 40px; border-radius: 16px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #f0f2f5;">
            <i class="bi bi-building" style="font-size: 48px; color: #667eea; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
            <h3 style="color: #2c3e50; margin-bottom: 8px;">Select a Hotel</h3>
            <p style="color: #6c757d; margin-bottom: 0;">Please select a hotel from the list above to view available rooms.</p>
        </div>
    @endif
</div>

{{-- CSS --}}
<style>
    .hotel-selection-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 16px;
    }

    .hotel-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .hotel-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15);
        border-color: #667eea;
    }

    .hotel-card.selected {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #764ba2;
    }

    .hotel-icon {
        font-size: 36px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 12px;
        color: #667eea;
        font-weight: 300;
    }

    .hotel-card.selected .hotel-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .hotel-name {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .hotel-card.selected .hotel-name {
        color: white;
    }

    /* Room Cards */
    .room-card {
        background: white;
        border-radius: 12px;
        padding: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 90px;
        position: relative;
        font-weight: 600;
        color: white;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .room-card.empty {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(40, 167, 69, 0.2);
    }

    .room-card.empty:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
    }

    .room-card.partial {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #333;
        box-shadow: 0 2px 8px rgba(255, 152, 0, 0.15);
    }

    .room-card.partial:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
    }

    .room-card.full {
        background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        color: white;
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.15);
        cursor: not-allowed;
    }

    .room-card.disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .room-card.selected {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.08);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* Rooms Grid */
    .rooms-grid {
        display: grid !important;
        grid-template-columns: repeat(5, 1fr) !important;
        gap: 8px !important;
        width: 100%;
    }

    /* Category Container */
    .rooms-grid-container {
        background: white;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f2f5;
        width: 100%;
    }

    /* Buttons */
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-modern:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-primary-modern:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .hotel-selection-container {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        }
        
        .rooms-grid {
            grid-template-columns: repeat(4, 1fr) !important;
        }
    }

    @media (max-width: 768px) {
        .hotel-selection-container {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .page-title {
            font-size: 28px;
        }

        .booking-detail {
            width: 100%;
        }
        
        .progress-wrapper {
            top: 80px;
        }
        
        .rooms-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    
    @media (max-width: 480px) {
        .rooms-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* Tooltip Styles */
    .tippy-box {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid #f0f2f5;
    }

    .tippy-content {
        padding: 0;
    }
</style>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css"/>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const roomCards = document.querySelectorAll('.room-card');
    roomCards.forEach(room => {
        const tooltipHtml = room.getAttribute('data-tooltip-html');
        if (tooltipHtml) {
            tippy(room, {
                content: tooltipHtml,
                allowHTML: true,
                interactive: true,
                placement: 'auto',
                theme: 'light',
                maxWidth: 350,
                duration: [300, 200]
            });
        }
    });

    // Hotel selection close button functionality
    const closeHotelBtn = document.getElementById('closeHotelBtn');
    const hotelSelectionSection = document.getElementById('hotelSelectionSection');
    
    if (closeHotelBtn && hotelSelectionSection) {
        // Show close button if a hotel is selected
        const selectedHotelId = "{{ $selectedHotelId ?? '' }}";
        if (selectedHotelId) {
            closeHotelBtn.style.display = 'flex';
        }

        closeHotelBtn.addEventListener('click', function() {
            hotelSelectionSection.style.display = 'none';
            closeHotelBtn.style.display = 'none';
        });

        // Show button when hotel is selected
        const hotelCards = document.querySelectorAll('.hotel-card');
        hotelCards.forEach(card => {
            card.addEventListener('click', function() {
                const href = this.getAttribute('data-href');
                if (href) {
                    setTimeout(() => {
                        closeHotelBtn.style.display = 'flex';
                    }, 100);
                }
            });
        });
    }
    
    const LS_KEY = 'roomSelections_v1';

    // Get elements
    const totalPersonsEl = document.getElementById('total_persons');
    const selectedCapacityEl = document.getElementById('selectedCapacity');
    const capacityNeededEl = document.getElementById('capacityNeeded');
    const roomsJsonEl = document.getElementById('rooms_json');
    const allotButton = document.getElementById('allotButton');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    const currentPageHotelId = "{{ $selectedHotelId ?? '' }}";

    let totalRequired = 0;
    if (totalPersonsEl && !isNaN(parseInt(totalPersonsEl.value))) {
        totalRequired = parseInt(totalPersonsEl.value);
    } else {
        try {
            totalRequired = parseInt("{{ $total_persons ?? 0 }}") || 0;
        } catch(e) {
            totalRequired = 0;
        }
    }

    let selectedRooms = [];
    let totalSelectedCapacity = 0;

    // Helpers
    function updateAllotButtonState() {
        if (!allotButton) return;
        allotButton.disabled = totalSelectedCapacity < totalRequired;
        allotButton.style.opacity = totalSelectedCapacity < totalRequired ? '0.5' : '1';
    }

    function updateProgressBar() {
        if (progressBar) {
            const percent = Math.round((totalSelectedCapacity / totalRequired) * 100);
            progressBar.style.width = percent + '%';
        }
        if (progressText) {
            progressText.textContent = totalSelectedCapacity;
        }
        if (selectedCapacityEl) {
            selectedCapacityEl.textContent = totalSelectedCapacity;
        }
        if (capacityNeededEl) {
            const needed = Math.max(0, totalRequired - totalSelectedCapacity);
            capacityNeededEl.textContent = needed;
        }
    }

    function saveSelectionsToStorage() {
        try { localStorage.setItem(LS_KEY, JSON.stringify(selectedRooms)); } catch (e) { console.warn(e); }
    }

    function loadSelectionsFromStorage() {
        try {
            const raw = localStorage.getItem(LS_KEY);
            selectedRooms = raw ? JSON.parse(raw) : [];
        } catch (e) {
            console.warn('loadSelections error', e);
            selectedRooms = [];
        }
    }

    function recalcTotalCapacity() {
        totalSelectedCapacity = selectedRooms.reduce((s, r) => s + (parseInt(r.capacity) || 0), 0);
    }

    function renderSelectedRoomUIForPage() {
        document.querySelectorAll('.room-card.selected').forEach(el => el.classList.remove('selected'));

        const forThisHotel = selectedRooms.filter(r => String(r.hotel_id) === String(currentPageHotelId));
        forThisHotel.forEach(r => {
            const selector = `.room-card[data-room="${r.room_number}"]`;
            const el = document.querySelector(selector);
            if (el) el.classList.add('selected');
        });

        recalcTotalCapacity();
        updateProgressBar();
        if (roomsJsonEl) roomsJsonEl.value = JSON.stringify(selectedRooms);
        updateAllotButtonState();
    }

    // Initialize from storage
    loadSelectionsFromStorage();
    renderSelectedRoomUIForPage();

    // Hotel card clicks
    const hotelCards = document.querySelectorAll('.hotel-card');
    if (hotelCards && hotelCards.length) {
        hotelCards.forEach(card => {
            card.addEventListener('click', function (e) {
                const targetHotelId = this.getAttribute('data-hotel-id');
                const href = this.getAttribute('data-href') || null;

                if (!href) {
                    saveSelectionsToStorage();
                    return;
                }

                if (selectedRooms.length > 0 && !selectedRooms.every(r => String(r.hotel_id) === String(targetHotelId))) {
                    Swal.fire({
                        title: 'You have selections from another hotel',
                        html: 'Do you want to keep them and add more rooms, or clear and start fresh?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Keep & Continue',
                        cancelButtonText: 'Clear & Start New',
                        confirmButtonColor: '#667eea',
                        cancelButtonColor: '#dc3545',
                    }).then(res => {
                        if (res.isConfirmed) {
                            saveSelectionsToStorage();
                            window.location.href = href;
                        } else {
                            selectedRooms = [];
                            saveSelectionsToStorage();
                            window.location.href = href;
                        }
                    });
                } else {
                    saveSelectionsToStorage();
                    window.location.href = href;
                }
            });
        });
    }

    // Room click logic
    const roomEls = document.querySelectorAll('.room-card');
    if (roomEls && roomEls.length) {
        roomEls.forEach(room => {
            room.addEventListener('click', function(e) {
                if (this.classList.contains('disabled') || parseInt(this.dataset.capacity || '0') <= 0) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Not Available', 
                        text: 'This room is fully booked for the selected dates.',
                        confirmButtonColor: '#667eea'
                    });
                    return;
                }

                const roomNumber = this.dataset.room;
                const roomCapacity = parseInt(this.dataset.capacity || '0');
                const hotelIdForRoom = this.dataset.hotel || currentPageHotelId;

                this.style.transform = 'scale(0.95)';
                setTimeout(() => this.style.transform = '', 100);

                const idx = selectedRooms.findIndex(r => 
                    String(r.hotel_id) === String(hotelIdForRoom) && 
                    String(r.room_number) === String(roomNumber)
                );

                if (idx > -1) {
                    totalSelectedCapacity -= parseInt(selectedRooms[idx].capacity || 0);
                    selectedRooms.splice(idx, 1);
                    this.classList.remove('selected');
                } else {
                    const remaining = totalRequired - totalSelectedCapacity;
                    if (remaining <= 0) {
                        Swal.fire({ 
                            icon: 'warning', 
                            title: 'Capacity Reached', 
                            text: 'You have already allocated enough rooms.',
                            confirmButtonColor: '#667eea'
                        });
                        return;
                    }

                    const assignedCapacity = Math.min(roomCapacity, remaining);
                    selectedRooms.push({ 
                        hotel_id: hotelIdForRoom, 
                        room_number: roomNumber, 
                        capacity: assignedCapacity 
                    });
                    totalSelectedCapacity += assignedCapacity;
                    this.classList.add('selected');
                }

                saveSelectionsToStorage();
                updateProgressBar();
                if (roomsJsonEl) {
                    roomsJsonEl.value = JSON.stringify(selectedRooms);
                }
                updateAllotButtonState();
            });
        });
    }

    // Form submit
    const roomForm = document.getElementById('roomForm');
    if (roomForm) {
        roomForm.addEventListener('submit', function (e) {
            e.preventDefault();
            loadSelectionsFromStorage();
            recalcTotalCapacity();

            if (selectedRooms.length === 0 || totalSelectedCapacity < totalRequired) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Cannot Submit', 
                    text: 'Please allocate rooms for all ' + totalRequired + ' members.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            document.querySelectorAll('input[name^="rooms["]').forEach(i => i.remove());

            selectedRooms.forEach((room, index) => {
                const hidHotel = document.createElement('input'); 
                hidHotel.type = 'hidden';
                hidHotel.name = `rooms[${index}][hotel_id]`; 
                hidHotel.value = room.hotel_id; 
                roomForm.appendChild(hidHotel);

                const rn = document.createElement('input'); 
                rn.type = 'hidden';
                rn.name = `rooms[${index}][room_number]`; 
                rn.value = room.room_number; 
                roomForm.appendChild(rn);

                const cp = document.createElement('input'); 
                cp.type = 'hidden';
                cp.name = `rooms[${index}][capacity]`; 
                cp.value = room.capacity; 
                roomForm.appendChild(cp);
            });

            const jsonInput = document.getElementById('rooms_json');
            if (jsonInput) jsonInput.value = JSON.stringify(selectedRooms);

            Swal.fire({
                title: 'Allocating Rooms',
                text: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                confirmButtonColor: '#667eea'
            });

            fetch(roomForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(roomForm)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            throw new Error(json.message || json.error || 'Server error');
                        } catch (e) {
                            throw new Error(e.message || 'Server error occurred');
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    localStorage.removeItem(LS_KEY);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        html: `<div style="text-align: left; margin: 20px 0;"><div style="margin-bottom: 12px;"><strong style="color: #667eea;">Hotel:</strong><br>${data.hotel_summary}</div><div><strong style="color: #667eea;">Rooms:</strong><br>${data.rooms.join(', ')}</div></div>`,
                        confirmButtonText: 'Continue',
                        confirmButtonColor: '#667eea',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = data.redirect_url || "{{ route('registration.list') }}";
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to allocate rooms',
                    confirmButtonColor: '#667eea'
                });
                Swal.hideLoading();
            });
        });
    }
});
</script>
@endsection
