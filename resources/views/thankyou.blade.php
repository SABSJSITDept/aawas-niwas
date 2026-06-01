    {{-- resources/views/thankyou.blade.php --}}
    <!DOCTYPE html>
    <html lang="hi">
    <head>
        <meta charset="UTF-8" />
        <title>Booking Confirmation — {{ $booking->booking_id ?? ($booking_id ?? '—') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            :root{
                --bg:#f7fafc;
                --card:#ffffff;
                --muted:#6b7280;
                --accent:#059669;
                --accent-dark:#065f46;
                --surface:#f9fafb;
                --border:#e5e7eb;
            }
            *{box-sizing:border-box}
            body{
                font-family: 'Inter', sans-serif;
                margin:0;
                background:var(--bg);
                -webkit-font-smoothing:antialiased;
                -moz-osx-font-smoothing:grayscale;
                padding:28px;
                display:flex;
                align-items:center;
                justify-content:center;
                min-height:100vh;
            }

            .card{
                width:100%;
                max-width:980px;
                background:var(--card);
                border:1px solid var(--border);
                border-radius:14px;
                box-shadow:0 10px 30px rgba(2,6,23,0.06);
                display:grid;
                grid-template-columns: 1fr 320px;
                overflow:hidden;
            }

            @media (max-width:880px){
                .card{grid-template-columns:1fr}
                .right { order: 2; }
                .left  { order: 1; }
            }

            .left{
                padding:34px 40px;
            }

            .logo{
                width:56px;height:56px;border-radius:12px;
                display:inline-flex;align-items:center;justify-content:center;
                background:linear-gradient(135deg,var(--accent),#10b981);
                color:#fff;font-weight:700;font-size:20px;
                box-shadow:0 6px 22px rgba(5,150,105,0.18);
                margin-right:14px;
            }

            .header{display:flex;align-items:center;margin-bottom:18px}
            .title{margin:0;font-size:20px;color:var(--accent-dark);font-weight:700}
            .subtitle{margin:0;color:var(--muted);font-size:13px}

            h1{font-size:22px;margin:14px 0 6px;color:#0f1724}
            .lead{color:var(--muted);margin-bottom:22px}

            .detail-grid{
                display:flex;flex-wrap:wrap;gap:14px;margin-bottom:22px;
            }

            .detail-box{
                background:var(--surface);
                border:1px solid var(--border);
                padding:12px 14px;border-radius:10px;
                min-width:150px;flex:1 1 150px;
            }
            .detail-box .label{font-size:12px;color:var(--muted);margin-bottom:6px}
            .detail-box .value{font-weight:600;color:#0b1220;font-size:15px}

            .info-row{display:flex;justify-content:space-between;padding:12px 0;border-top:1px dashed var(--border)}
            .info-left{color:var(--muted);font-size:14px}
            .info-right{font-weight:600;color:#0b1220}

            .footer-note{margin-top:18px;color:var(--muted);font-size:13px}

            .right{
                background:linear-gradient(180deg,#fbfdfc,#f3f7f6);
                padding:28px; border-left:1px solid var(--border);
                display:flex;flex-direction:column;align-items:center;justify-content:center;
                gap:12px;
            }

            .booking-box{
                width:100%;background:var(--card);border-radius:10px;border:1px solid var(--border);
                padding:18px;text-align:center;box-shadow:0 6px 18px rgba(3,10,28,0.04);
            }

            .booking-label{font-size:13px;color:var(--muted)}
            .booking-value{font-size:22px;font-weight:800;color:var(--accent);margin-top:6px}
            .booking-meta{font-size:13px;color:var(--muted);margin-top:6px}

            .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;justify-content:center}
            .btn{
                padding:10px 14px;border-radius:9px;border:0;font-weight:700;cursor:pointer;
                font-size:14px;transition:all .15s ease;
            }
            .btn-copy{background:#fff;border:1px solid var(--border);color:#0b1220}
            .btn-copy:hover{transform:translateY(-2px)}
            .btn-print{background:linear-gradient(90deg,var(--accent),#10b981);color:#fff}
            .btn-print:hover{opacity:.95}
            .btn-home{background:linear-gradient(90deg,#3b82f6,#2563eb);color:#fff}
            .btn-home:hover{opacity:.95}

            .help{margin-top:14px;color:var(--muted);font-size:13px;text-align:center}
            .muted-strong{color:var(--accent);font-weight:700}

            /* print friendly */
            @media print{
                body{background:#fff}
                .card{box-shadow:none;border:none}
                .actions,.help{display:none}
            }
        </style>
    </head>
    <body>

    @php
        // safe values: $booking may be null if route passed only booking_id or closure used
        $displayBookingId = $booking->booking_id ?? ($booking_id ?? null);
        $displayName = $booking->name ?? null;
        $displayPhone = $booking->phone ?? null;
        $checkIn = $booking->check_in_date ?? null;
        $checkOut = $booking->check_out_date ?? null;
        $createdAt = $booking->created_at ?? null;

        // People counts (fall back gracefully)
        $totalPersons = $booking->total_persons 
                        ?? ($booking->total_members ? ($booking->total_members + 1) : null)
                        ?? ($booking_id ? null : null);

        $totalMale = $booking->total_male ?? null;
        $totalFemale = $booking->total_female ?? null;

        // sixty+ breakdown if present
        $sixtyPlusTotal = $booking->sixty_plus_members ?? null;
        $sixtyPlusMale = $booking->sixty_plus_male ?? null;
        $sixtyPlusFemale = $booking->sixty_plus_female ?? null;
    @endphp

    <div class="card" role="region" aria-label="Booking confirmation">
        <div class="left">
            <div class="header">
                <div class="logo" aria-hidden="true">S</div>
                <div>
                    <div class="title">SABSJS — बुकिंग पुष्टि</div>
                    <div class="subtitle">आपका पंजीकरण सफलतापूर्वक पूरा हुआ। कृपया बुकिंग आईडी सुरक्षित रखें।</div>
                </div>
            </div>

            <h1>धन्यवाद! आपकी बुकिंग सफल हुई 🙏</h1>
            <p class="lead">नीचे बुकिंग का सार संक्षेप में दिया गया है — स्क्रीनशॉट अथवा कॉपी कर के सुरक्षित रखें।</p>

            <div class="detail-grid" aria-hidden="{{ $displayBookingId ? 'false' : 'true' }}">
                <div class="detail-box">
                    <div class="label">Booking</div>
                    <div class="value" id="bookingIdText">{{ $displayBookingId ?? '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">नाम</div>
                    <div class="value">{{ $displayName ?? '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">फ़ोन</div>
                    <div class="value">{{ $displayPhone ?? '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">चेक-इन</div>
                    <div class="value">{{ $checkIn ? \Carbon\Carbon::parse($checkIn)->format('d M, Y') : '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">चेक-आउट</div>
                    <div class="value">{{ $checkOut ? \Carbon\Carbon::parse($checkOut)->format('d M, Y') : '—' }}</div>
                </div>
            </div>

            {{-- People summary as boxes for better visual --}}
            <div class="detail-grid" style="margin-top:6px;">
                <div class="detail-box">
                    <div class="label">कुल व्यक्ति</div>
                    <div class="value">{{ $totalPersons ?? '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">पुरुष</div>
                    <div class="value">{{ $totalMale ?? '—' }}</div>
                </div>

                <div class="detail-box">
                    <div class="label">महिला</div>
                    <div class="value">{{ $totalFemale ?? '—' }}</div>
                </div>
            </div>

            {{-- Optional 60+ breakdown --}}
            @if($sixtyPlusTotal || $sixtyPlusMale || $sixtyPlusFemale)
                <div style="margin-top:12px;">
                    <div class="info-row" role="group" aria-label="60 plus">
                        <div class="info-left">60 वर्ष से अधिक (कुल)</div>
                        <div class="info-right">{{ $sixtyPlusTotal ?? '-' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-left">60+ पुरुष</div>
                        <div class="info-right">{{ $sixtyPlusMale ?? '-' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-left">60+ महिला</div>
                        <div class="info-right">{{ $sixtyPlusFemale ?? '-' }}</div>
                    </div>
                </div>
            @endif

            <div class="info-row" style="margin-top:12px;">
                <div class="info-left">यात्रा का प्रकार</div>
                <div class="info-right">{{ ucfirst($booking->travel_type ?? '-') }}</div>
            </div>

            @if(!empty($booking->aadhar_number))
                <div class="info-row">
                    <div class="info-left">आधार</div>
                    <div class="info-right">{{ $booking->aadhar_number }}</div  >
                </div>
            @endif

            <p class="footer-note">आपको SMS के जरिए भी पुष्टि भेजी जा चुकी है। परिवर्तन/सहायता के लिए कॉल करें: <strong class="muted-strong">+91  82333 32028</strong></p>
        </div>

        <div class="right" aria-hidden="false">
            <div class="booking-box" role="status" aria-live="polite">
                <div class="booking-label">Booking ID</div>
                <div class="booking-value" id="bookingValue">{{ $displayBookingId ?? '—' }}</div>
                <div class="booking-meta">{{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d M, Y • h:i A') : '' }}</div>

                <div class="actions" role="toolbar" aria-label="Actions">
                    <button class="btn btn-copy" id="copyBtn" type="button" aria-label="Copy booking id">📋 कॉपी करें</button>
                    <button class="btn btn-print" id="printBtn" type="button" aria-label="Print">🖨️ प्रिंट करें</button>
                    <button class="btn btn-home" id="homeBtn" type="button" aria-label="Go to home">🏠 होम</button>

                
                </div>
            </div>

            <div class="help">यदि आवश्यकता हो तो हमसे संपर्क करें: <strong class="muted-strong"> 82333 32028</strong></div>
        </div>
    </div>

    <script>
        (function(){
            // Back button should go to home
            window.history.replaceState(null, '', window.location.href);
            window.addEventListener('popstate', () => {
                window.location.href = '/';
            });
            
            const copyBtn = document.getElementById('copyBtn');
            const printBtn = document.getElementById('printBtn');
            const homeBtn = document.getElementById('homeBtn');
            const bookingValueEl = document.getElementById('bookingValue');
            const bookingIdText = document.getElementById('bookingIdText');

            copyBtn?.addEventListener('click', async () => {
                const text = (bookingValueEl?.innerText || bookingIdText?.innerText || '').trim();
                if (!text || text === '—') return;
                try {
                    await navigator.clipboard.writeText(text);
                    copyBtn.innerText = '✅ कॉपी हो गया';
                    setTimeout(()=> copyBtn.innerText = '📋 कॉपी करें', 2000);
                } catch (e) {
                    // fallback prompt
                    const fallback = prompt('कृपया मैन्युअली कॉपी करें:', text);
                    if (fallback !== null) {
                        copyBtn.innerText = '✅ कॉपी हुआ';
                        setTimeout(()=> copyBtn.innerText = '📋 कॉपी करें', 2000);
                    }
                }
            });

            printBtn?.addEventListener('click', () => window.print());
            
            homeBtn?.addEventListener('click', () => window.location.href = '/');
        })();
    </script>
    
    </body>
    </html>
