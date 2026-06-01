<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>बुकिंग पुष्टि — {{ $booking->booking_id ?? '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 16px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.05);
            max-width: 950px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 300px;
            overflow: hidden;
        }

        @media (max-width: 820px) {
            .card {
                grid-template-columns: 1fr;
            }
        }

        .left {
            padding: 40px 48px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .home-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 24px;
            transition: transform 0.2s;
        }

        .home-button:hover {
            transform: translateY(-2px);
        }

        .logo {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 700;
            font-size: 20px;
            box-shadow: 0 4px 14px rgba(5,150,105,0.25);
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            color: #065f46;
        }

        .header p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        h1 {
            color: #111827;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 32px;
        }

        .detail-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
        }

        .detail-box {
            flex: 1 1 150px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 18px;
            text-align: left;
        }

        .detail-box div:first-child {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .detail-box div:last-child {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-left {
            color: #6b7280;
            font-size: 14px;
        }

        .info-right {
            font-weight: 600;
            color: #111827;
        }

        .footer-note {
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
        }

        .right {
            background: linear-gradient(180deg, #f9fafb, #f3f4f6);
            border-left: 1px solid #e5e7eb;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .booking-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .booking-id {
            font-size: 16px;
            font-weight: 600;
            color: #065f46;
        }

        .booking-value {
            font-size: 20px;
            font-weight: 700;
            color: #059669;
            margin-top: 4px;
        }

        .booking-date {
            color: #6b7280;
            font-size: 13px;
            margin-top: 6px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 14px;
        }

        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy {
            background: #f3f4f6;
            color: #111827;
            border: 1px solid #e5e7eb;
        }

        .btn-copy:hover {
            background: #e5e7eb;
        }

        .btn-print {
            background: linear-gradient(90deg, #059669, #10b981);
            color: white;
        }

        .btn-print:hover {
            opacity: 0.9;
        }

        .support {
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
        }

        .support strong {
            color: #059669;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="left">
        <div class="header">
            <div class="logo">S</div>
            <div>
                <h2>SABSJS — बुकिंग पुष्टि</h2>
                <p>सहयोग के लिए धन्यवाद — आपकी बुकिंग सुरक्षित है।</p>
            </div>
        </div>

        <h1>धन्यवाद! आपकी बुकिंग सफल रही 🙏</h1>
        <p class="subtitle">नीचे आपके बुकिंग का सारांश दिया गया है — कृपया इसे संभाल कर रखें।</p>

        <div class="detail-grid">
            <div class="detail-box">
                <div>Booking</div>
                <div id="bookingIdText">{{ $booking->booking_id ?? '—' }}</div>
            </div>
            @if(!empty($booking->name))
            <div class="detail-box">
                <div>नाम</div>
                <div>{{ $booking->name }}</div>
            </div>
            @endif
            @if(!empty($booking->phone))
            <div class="detail-box">
                <div>फ़ोन</div>
                <div>{{ $booking->phone }}</div>
            </div>
            @endif
            @if(!empty($booking->check_in_date))
            <div class="detail-box">
                <div>चेक-इन</div>
                <div>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, Y') }}</div>
            </div>
            @endif
            @if(!empty($booking->check_out_date))
            <div class="detail-box">
                <div>चेक-आउट</div>
                <div>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}</div>
            </div>
            @endif
        </div>

        <div class="info-row">
            <div class="info-left">कुल व्यक्ति</div>
            <div class="info-right">{{ $booking->total_persons ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-left">यात्रा का प्रकार</div>
            <div class="info-right">{{ ucfirst($booking->travel_type ?? '-') }}</div>
        </div>

        @if(!empty($booking->aadhar_number))
        <div class="info-row">
            <div class="info-left">आधार</div>
            <div class="info-right">{{ $booking->aadhar_number }}</div>
        </div>
        @endif

        <p class="footer-note">आप SMS के माध्यम से भी पुष्टि प्राप्त कर चुके हैं। किसी भी परिवर्तन के लिए कृपया <strong>+91 63753 59089</strong> पर संपर्क करें।</p>
        
        <a href="{{ url('/') }}" class="home-button">🏠 होम पेज पर जाएं</a>
    </div>

    <div class="right">
        <div class="booking-box">
            <div class="booking-id">Booking ID</div>
            <div class="booking-value">{{ $booking->booking_id ?? '—' }}</div>
            <div class="booking-date">{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d M, Y • h:i A') : '' }}</div>

            <div class="actions">
                <button class="btn btn-copy" id="copyBtn">📋 कॉपी करें</button>
                <button class="btn btn-print" id="printBtn">🖨️ प्रिंट करें</button>
            </div>
        </div>

        <div class="support">
            यदि आपको सहायता चाहिए — <strong>63753 59089</strong>
        </div>
    </div>
</div>

<script>
    const copyBtn = document.getElementById('copyBtn');
    const bookingId = document.getElementById('bookingIdText')?.innerText.trim();
    copyBtn?.addEventListener('click', () => {
        if (!bookingId) return;
        navigator.clipboard.writeText(bookingId).then(() => {
            copyBtn.textContent = '✅ कॉपी हो गया';
            setTimeout(() => copyBtn.textContent = '📋 कॉपी करें', 2000);
        });
    });

    document.getElementById('printBtn')?.addEventListener('click', () => window.print());
</script>
</body>
</html>
