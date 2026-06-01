<!DOCTYPE html>
<html>
<head>
    <title>हमारी Location - आवास निवास</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --orange-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        body {
            background-color: #f8fafc;
            min-height: 100vh;
            padding-top: 80px;
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
        }

        /* Hero Section */
        .location-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
            padding: 80px 0 160px;
            position: relative;
            overflow: hidden;
            margin-top: 0;
            color: #fff;
        }

        .location-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(255,255,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .location-hero::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 100" xmlns="http://www.w3.org/2000/svg"><path d="M0,50 C320,-50 420,150 720,50 C1020,-50 1120,150 1440,50 L1440,100 L0,100 Z" fill="%23f8fafc"/></svg>') center/cover no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            animation: fadeInDown 0.6s ease;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 16px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease;
        }

        /* Main Content */
        .location-container {
            margin-top: -100px;
            position: relative;
            z-index: 10;
        }

        .location-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.03);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            position: relative;
            padding: 24px;
        }

        .map-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .map-container iframe {
            display: block;
            width: 100%;
            height: 500px;
            border: none;
        }

        /* Address Card */
        .address-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.2);
            margin-top: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .address-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: url('data:image/svg+xml;utf8,<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1.5" fill="%23ffffff" fill-opacity="0.05"/></svg>');
        }

        .address-content {
            position: relative;
            z-index: 2;
        }

        .address-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }

        .address-title {
            font-size: 20px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .address-text {
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.6;
        }

        .address-text span {
            display: block;
            font-weight: 400;
            font-size: 1.05rem;
            color: #cbd5e1;
            margin-top: 4px;
        }

        /* Info Cards */
        .info-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            padding: 24px 0 0;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 28px;
            text-align: left;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .info-card:hover {
            transform: translateY(-5px);
            background: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            border-color: #e2e8f0;
        }

        .info-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            flex-shrink: 0;
        }

        .info-icon-wrapper.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .info-icon-wrapper.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .info-icon-wrapper.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }

        .info-card-text h5 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .info-card-text p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Action Buttons */
        .action-buttons {
            padding: 24px 0 0;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            color: #fff;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            color: #fff;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .btn-success-gradient {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }

        .btn-action i {
            font-size: 22px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            50% {
                transform: translate(-10px, -10px) scale(1.05);
            }
        }

        @media (max-width: 768px) {
            .address-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 24px;
            }

            .info-card {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .info-icon-wrapper {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

    {{-- ✅ Header --}}
    @include('includes.header')

    <!-- Hero Section -->
    <div class="location-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="bi bi-geo-alt-fill me-2"></i>Find Us Here
                </div>
                <h1 class="hero-title">📍 हमारी Location</h1>
                <p class="hero-subtitle">Seva Sadan Chabali Ghati, Bikaner में आपका स्वागत है</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container location-container mb-5 pb-5">
        <div class="location-card">
            
            <!-- Google Map -->
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps?q=Seva+Sadan+Chabali+Ghati+Bikaner&hl=en&z=17&output=embed" 
                    allowfullscreen 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Address Section -->
            <div class="address-card">
                <div class="address-icon">
                    <i class="bi bi-pin-map-fill"></i>
                </div>
                <div class="address-content">
                    <h4 class="address-title">Venue Location</h4>
                    <div class="address-text">
                        Seva Sadan Chabali Ghati, Bikaner
                        <span>Bikaner, Rajasthan, India<br>📍 Seva Sadan 🇮🇳</span>
                    </div>
                </div>
            </div>

          

            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="https://share.google/C9RfibMFPueQ1JBes" 
                           target="_blank" 
                           class="btn btn-primary-gradient btn-action w-100">
                            <i class="bi bi-navigation-fill"></i>
                            <span>Get Directions</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="https://share.google/C9RfibMFPueQ1JBes" 
                           target="_blank" 
                           class="btn btn-success-gradient btn-action w-100">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span>Open in Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Second Location Card -->
       
    </div>

    {{-- ✅ Footer --}}
    @include('includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
