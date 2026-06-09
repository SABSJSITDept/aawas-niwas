<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    @php
        $forImage = $renderForImage ?? false;
        $hindiFont = $forImage ? "'Noto Sans Devanagari', sans-serif" : "'noto sans devanagari', 'DejaVu Sans', sans-serif";
        if (! $forImage) {
            $devanagariRegular = str_replace('\\', '/', resource_path('fonts/NotoSansDevanagari-Regular.ttf'));
            $devanagariBold = str_replace('\\', '/', resource_path('fonts/NotoSansDevanagari-Bold.ttf'));
        }
    @endphp
    @if($forImage)
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        @unless($forImage)
        @font-face {
            font-family: 'noto sans devanagari';
            font-style: normal;
            font-weight: normal;
            src: url('{{ $devanagariRegular }}') format('truetype');
        }
        @font-face {
            font-family: 'noto sans devanagari';
            font-style: normal;
            font-weight: bold;
            src: url('{{ $devanagariBold }}') format('truetype');
        }

        @page {
            margin: 10mm 15mm;
            size: A4;
        }
        @endunless
        
        body {
            font-family: {!! $hindiFont !!};
            font-size: 11px;
            line-height: 1.4;
            color: #374151; /* gray-700 */
            margin: 0;
            padding: 0;
            background: #ffffff;
            @if($forImage)
            width: 794px;
            height: 1123px;
            box-sizing: border-box;
            padding: 14px 16px;
            overflow: hidden;
            font-size: 12px;
            @endif
        }

        .container {
            width: 100%;
            @if($forImage)
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            @endif
        }

        /* Utility classes */
        .font-bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-gray-500 { color: #6b7280; }
        .text-gray-700 { color: #374151; }
        .text-gray-900 { color: #111827; }
        .text-blue-600 { color: #2563eb; }
        .bg-gray-50 { background-color: #f9fafb; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .mb-2 { margin-bottom: 8px; }
        .w-full { width: 100%; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success { background: #10b981; } /* emerald-500 */
        .badge-family { background: #3b82f6; } /* blue-500 */
        .badge-group { background: #14b8a6; } /* teal-500 */
        .badge-vip { background: #8b5cf6; } /* violet-500 */

        /* Header Layout */
        .header-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 3px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            max-width: 145px;
            max-height: 90px;
            width: auto;
            height: auto;
        }
        .logo-img-primary {
            max-width: 200px;
            max-height: 125px;
        }
        .title {
            font-family: {!! $hindiFont !!};
            font-size: 20px;
            font-weight: bold;
            color: #1f2937; /* gray-800 */
            margin-bottom: 2px;
            text-transform: none;
            letter-spacing: 0;
        }
        .title-sub {
            font-family: {!! $hindiFont !!};
            font-size: 13px;
            font-weight: normal;
            color: #4b5563;
            margin-bottom: 6px;
        }
        .subtitle {
            font-size: 12px;
            color: #6b7280; /* gray-500 */
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section Cards */
        .card {
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 10px;
            margin-bottom: 8px;
            overflow: hidden;
            background: #ffffff;
        }
        .card-header {
            background-color: #f3f4f6; /* gray-100 */
            padding: 6px 15px;
            font-size: 12px;
            font-weight: bold;
            color: #1f2937; /* gray-800 */
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .card-body {
            padding: 8px 15px;
        }

        /* Info Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 6px 10px;
            border-bottom: 1px dashed #e5e7eb;
            vertical-align: top;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .label {
            width: 40%;
            font-weight: bold;
            color: #6b7280; /* gray-500 */
            font-size: 11px;
        }
        .value {
            width: 60%;
            color: #111827; /* gray-900 */
            font-size: 12px;
            font-weight: bold;
        }

        /* Highlight Box */
        .highlight-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        /* Members Grid */
        .members-grid {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .members-grid td {
            padding: 8px;
            width: 33.33%;
            border-right: 1px solid #e5e7eb;
        }
        .members-grid td:last-child {
            border-right: none;
        }
        .member-num {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            display: block;
            line-height: 1.1;
            margin-bottom: 1px;
        }
        .member-text {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Map Button */
        .map-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Alerts */
        .alert-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 15px; /* ensure space before fixed footer */
        }
        .alert-title {
            font-family: {!! $hindiFont !!};
            color: #b45309;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: none;
        }
        .alert-content {
            font-family: {!! $hindiFont !!};
            color: #92400e;
            font-size: 10px;
        }

        .emergency-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Footer */
        .footer {
            @if($forImage)
            position: static;
            margin-top: 16px;
            @else
            position: fixed;
            bottom: -5mm;
            left: 0;
            right: 0;
            @endif
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        @if($forImage)
        .logo-img-primary { max-width: 240px; max-height: 150px; }
        .header-table { margin-bottom: 10px; padding-bottom: 10px; }
        .card { margin-bottom: 10px; }
        .title { font-size: 24px; }
        .title-sub { font-size: 15px; }
        .subtitle { font-size: 13px; }
        .card-header { font-size: 13px; padding: 8px 15px; }
        .value { font-size: 13px; }
        .member-num { font-size: 22px; }
        .alert-content { font-size: 11px; line-height: 1.7; }
        .footer { margin-top: 8px; font-size: 11px; }
        @endif
    </style>
</head>
<body>
<div class="container">
    
    <!-- Booking Type Badge -->
    @php
        $bookingTypeClass = 'badge-family';
        $bookingTypeText = 'Family Booking';
        if(strpos($booking_id, 'V-') === 0) {
            $bookingTypeClass = 'badge-vip';
            $bookingTypeText = 'VIP Booking';
        } elseif(strpos($booking_id, 'G-') === 0) {
            $bookingTypeClass = 'badge-group';
            $bookingTypeText = 'Group Booking';
        }
    @endphp
    
    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 25%; text-align: left;">
                <img src="{{ $logo_src ?? $logo }}" class="logo-img logo-img-primary" alt="Logo">
            </td>
            <td style="width: 50%; text-align: center;">
                <div class="title">समर्पण महोत्सव - 2026</div>
                <div class="title-sub">बीकानेर, राजस्थान</div>
                <div class="subtitle">Room Booking Confirmation</div>
                <div>
                    <span class="badge {{ $bookingTypeClass }}" style="margin-right: 8px;">{{ $bookingTypeText }}</span>
                    <span class="badge badge-success">✓ CONFIRMED</span>
                </div>
            </td>
            <td style="width: 25%; text-align: right;">
                <img src="{{ $logo2_src ?? $logo2 }}" class="logo-img" alt="Logo 2">
            </td>
        </tr>
    </table>

    <!-- Booking Details & Schedule Combined -->
    <div class="card">
        <div class="card-header">Guest & Schedule Details</div>
        <div class="card-body" style="padding: 0;">
            <table class="w-full" style="border-collapse: collapse;">
                <tr>
                    <!-- Left Column: Personal Info -->
                    <td style="width: 50%; padding: 15px; vertical-align: top; border-right: 1px solid #e5e7eb;">
                        <table class="info-table" style="border-bottom: none;">
                            <tr>
                                <td class="label" style="border-bottom: none; padding-top: 0;">Name:</td>
                                <td class="value" style="border-bottom: none; padding-top: 0;">{{ $name }}</td>
                            </tr>
                            <tr>
                                <td class="label" style="border-bottom: none;">Booking ID:</td>
                                <td class="value" style="border-bottom: none;"><span class="highlight-box">{{ $booking_id }}</span></td>
                            </tr>
                            <tr>
                                <td class="label" style="border-bottom: none; padding-bottom: 0;">Mobile:</td>
                                <td class="value" style="border-bottom: none; padding-bottom: 0;">{{ $mobile }}</td>
                            </tr>
                        </table>
                    </td>
                    
                    <!-- Right Column: Schedule -->
                    <td style="width: 50%; padding: 15px; vertical-align: top;">
                        <table class="info-table" style="border-bottom: none;">
                            <tr>
                                <td class="label" style="border-bottom: none; padding-top: 0;">Check-in:</td>
                                <td class="value" style="border-bottom: none; padding-top: 0;">{{ $check_in }}</td>
                            </tr>
                            <tr>
                                <td class="label" style="border-bottom: none;">Check-out:</td>
                                <td class="value" style="border-bottom: none;">{{ $check_out }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Members Overview -->
    <div class="card">
        <div class="card-header">Members Overview</div>
        <div class="card-body bg-gray-50" style="padding: 0;">
            <table class="members-grid">
                <tr>
                    <td>
                        <span class="member-num">{{ $total_members }}</span>
                        <span class="member-text">Total Persons</span>
                    </td>
                    <td>
                        <span class="member-num" style="color: #059669;">{{ $male }}</span>
                        <span class="member-text">Male</span>
                    </td>
                    <td>
                        <span class="member-num" style="color: #db2777;">{{ $female }}</span>
                        <span class="member-text">Female</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Hotel Information -->
    <div class="card">
        <div class="card-header">Accommodation Details</div>
        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="label" style="width: 30%;">Hotel Name:</td>
                    <td class="value" style="width: 70%;">{{ $hotel_name }}</td>
                </tr>
                <tr>
                    <td class="label" style="width: 30%;">Room Number:</td>
                    <td class="value" style="width: 70%;"><span class="highlight-box" style="font-size: 16px;">{{ $room_number }}</span></td>
                </tr>
                <tr>
                    <td class="label" style="width: 30%;">Hotel Address:</td>
                    <td class="value" style="width: 70%;">
                        <div style="line-height: 1.5; margin-bottom: 8px;">{{ $hotel_address }}</div>
                        @if(!empty($google_maps_link))
                            <a href="{{ $google_maps_link }}" class="map-btn">📍 View on Google Maps</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label" style="width: 30%;">Contact Person:</td>
                    <td class="value" style="width: 70%;">{{ $contact_person }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="emergency-box">
        Emergency Contact | 24x7 Helpline: +91-XXXXX-XXXXX
    </div>

    <!-- Instructions -->
    <div class="alert-box">
        <div class="alert-title">Important Instructions / महत्वपूर्ण निर्देश</div>
        <ul class="alert-content" style="margin: 0; padding-left: 20px; line-height: 1.8;">
            <li>Please bring this confirmation document during check-in / कृपया चेक-इन के दौरान यह पुष्टिकरण दस्तावेज साथ लाएं।</li>
            <li>Ensure you follow the check-in and check-out timings strictly / कृपया चेक-इन और चेक-आउट समय का कड़ाई से पालन करें।</li>
            <li>Contact the helpline for any issues or assistance / किसी भी समस्या या सहायता के लिए हेल्पलाइन से संपर्क करें।</li>
            <li>Keep your original photo ID proof handy.</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="font-bold text-gray-700" style="margin-bottom: 4px; font-size: 13px; font-family: {!! $hindiFont !!};">समर्पण महोत्सव - 2026 | बीकानेर, राजस्थान</div>
        <div>Computer-generated document • No signature required</div>
        <div style="margin-top: 4px;">Generated: {{ date('d-m-Y H:i') }} | ID: {{ $booking_id }}</div>
    </div>

</div>
</body>
</html>