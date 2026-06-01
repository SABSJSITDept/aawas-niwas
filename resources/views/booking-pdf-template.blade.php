<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            background: white;
            border: 2px solid #ff6b35;
            padding: 12px;
            margin: 0;
        }

        .header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ff6b35;
            width: 100%;
        }

        .header-table {
            width: 100%;
            table-layout: fixed;
        }

        .logo-cell {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }

        .header-content-cell {
            text-align: center;
            vertical-align: middle;
            padding: 0 20px;
        }

        .logo-left {
            width: 160px;
            height: 160px;
        }

        .logo-right {
            width: 180px;
            height: 150px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #ff6b35;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 15px;
            color: #666;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .status-badge {
            background: #28a745;
            color: white;
            padding: 3px 10px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 8px;
            display: inline-block;
            margin-top: 3px;
        }

        .booking-type-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }

        .family-badge { background: #ffc107; }
        .vip-badge { background: #28a745; }
        .group-badge { background: #17a2b8; }

        .section {
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .section-header {
            background: #ff6b35;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10px;
            margin: 0;
        }

        .section-content {
            padding: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .info-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            width: 40%;
        }

        .info-value {
            color: #212529;
            width: 60%;
        }

        .highlight {
            background: #fff3cd;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            color: #856404;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .member-cell {
            text-align: center;
            padding: 8px;
            border: 2px solid #dee2e6;
            width: 33.33%;
        }

        .member-number {
            font-size: 16px;
            font-weight: bold;
            color: #ff6b35;
            display: block;
            margin-bottom: 3px;
        }

        .member-label {
            font-size: 10px;
            color: #6c757d;
            font-weight: 600;
        }

        .date-info {
            background: #f8f9fa;
            padding: 10px;
            border-left: 3px solid #17a2b8;
            margin: 8px 0;
        }

        .hotel-info {
            background: #e8f5e8;
            padding: 12px;
            border-left: 3px solid #28a745;
            margin: 10px 0;
        }

        .contact-info {
            background: #e6f3ff;
            padding: 12px;
            border-left: 3px solid #007bff;
            margin: 10px 0;
        }

        .map-section {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .map-link {
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 8px;
        }

        .instructions {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 3px;
            padding: 6px;
            margin: 8px 0;
        }

        .instructions ul {
            margin: 0;
            padding-left: 15px;
        }

        .instructions li {
            margin-bottom: 2px;
            color: #856404;
        }

        .emergency-contact {
            background: #f8d7da;
            border: 1px solid #dc3545;
            border-radius: 3px;
            padding: 6px;
            margin: 8px 0;
            text-align: center;
        }

        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #dee2e6;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
        }

        .page-break {
            page-break-before: always;
        }

        .footer-brand {
            font-weight: bold;
            color: #ff6b35;
            margin-bottom: 3px;
        }

        .divider {
            height: 2px;
            background: #ff6b35;
            margin: 15px 0;
            border-radius: 1px;
        }

        .priority-notice {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
            padding: 12px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            color: #856404;
        }
    </style>
</head>
<body>
<div class="container">
    
    <!-- Booking Type Badge -->
    @php
        $bookingTypeClass = 'family-badge';
        $bookingTypeText = 'Family Booking';
        if(strpos($booking_id, 'V-') === 0) {
            $bookingTypeClass = 'vip-badge';
            $bookingTypeText = 'VIP Booking';
        } elseif(strpos($booking_id, 'G-') === 0) {
            $bookingTypeClass = 'group-badge';
            $bookingTypeText = 'Group Booking';
        }
    @endphp
    
    <!-- Header -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ $logo }}" class="logo-left" alt="Left Logo">
                </td>
                <td class="header-content-cell">
                    <div class="title">Akshay Tritya Mohatsav 2026 - Nokha</div>
                    <div class="subtitle">Room Booking Confirmation</div>
                    <div class="status-badge">✓ CONFIRMED</div>
                </td>
                <td class="logo-cell">
                    <img src="{{ $logo2 }}" class="logo-right" alt="Right Logo">
                </td>
            </tr>
        </table>
    </div>

    <!-- Personal Information -->
    <div class="section">
        <div class="section-header">Personal Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">Name:</td>
                    <td class="info-value"><span class="highlight">{{ $name }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Booking ID:</td>
                    <td class="info-value"><span class="highlight">{{ $booking_id }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Mobile:</td>
                    <td class="info-value">{{ $mobile }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Members Information -->
    <div class="section">
        <div class="section-header">Members Information</div>
        <div class="section-content">
            <table class="members-table">
                <tr>
                    <td class="member-cell">
                        <span class="member-number">{{ $total_members }}</span>
                        <div class="member-label">Total Members</div>
                    </td>
                    <td class="member-cell">
                        <span class="member-number">{{ $male }}</span>
                        <div class="member-label">Male</div>
                    </td>
                    <td class="member-cell">
                        <span class="member-number">{{ $female }}</span>
                        <div class="member-label">Female</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Check-in/Check-out Information -->
    <div class="section">
        <div class="section-header">Check-in & Check-out Details</div>
        <div class="section-content">
            <div class="date-info">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Check-in:</td>
                        <td class="info-value">{{ $check_in }}</td>
                    </tr>
                </table>
            </div>
            <div class="date-info">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Check-out:</td>
                        <td class="info-value">{{ $check_out }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Hotel & Room Information -->
    <div class="section">
        <div class="section-header">Hotel & Room Information</div>
        <div class="section-content">
            <div class="hotel-info">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Hotel Name:</td>
                        <td class="info-value">{{ $hotel_name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Room Number:</td>
                        <td class="info-value"><span class="highlight">{{ $room_number }}</span></td>
                    </tr>
                </table>
            </div>
            
            <div class="contact-info">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Address:</td>
                        <td class="info-value">{{ $hotel_address }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Contact:</td>
                        <td class="info-value">{{ $contact_person }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div class="section page-break">
        <div class="section-header">Location Information</div>
        <div class="section-content">
            <div class="map-section">
                @if(!empty($google_maps_link))
                    <div style="margin-bottom: 10px;">
                        <strong>View on Google Maps</strong>
                    </div>
                    <a href="{{ $google_maps_link }}" class="map-link">Open in Maps</a>
                    <div style="margin-top: 10px; font-size: 9px; color: #6c757d; word-break: break-all;">
                        {{ $google_maps_link }}
                    </div>
                @else
                    <div style="color: #6c757d;">Map location not provided</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="emergency-contact">
        <div style="font-weight: bold; margin-bottom: 3px; color: #721c24; font-size: 9px;">
            Emergency Contact: 24x7 Helpline: +91-XXXXX-XXXXX | Event Coordinator: +91-XXXXX-XXXXX
        </div>
    </div>

    <!-- Important Instructions -->
    <div class="instructions">
        <div style="font-weight: bold; margin-bottom: 10px; color: #856404;">
            Important Instructions
        </div>
        <ul>
            <li>Please bring this confirmation document during check-in</li>
            <li>Contact for any issues or assistance required</li>
            <li>Follow  guidelines and check-in/out timings</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-brand">Akshay Tritya Mohatsav 2026 - Nokha</div>
        <div style="margin-bottom: 3px; font-size: 9px;">
            Computer-generated document • No signature required
        </div>
        <div style="font-size: 8px; color: #adb5bd;">
            Generated: {{ date('d-m-Y H:i:s') }} | ID: {{ $booking_id }}
        </div>
    </div>
</div>
</body>
</html>