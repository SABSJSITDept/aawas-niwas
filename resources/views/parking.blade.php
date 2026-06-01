@extends('layouts.app')

@section('content')
<div class="page-wrap">

    @include('includes.header')

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Professional Styles -->
    <style>
        :root{
            --primary: #4338ca;
            --primary-dark: #3730a3;
            --secondary: #6c757d;
            --success: #198754;
            --bg-soft: #f8fafc;
            --bg-lighter: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 6px 18px rgba(14, 30, 37, 0.12);
            --shadow-lg: 0 12px 24px rgba(14, 30, 37, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }  

        * {
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            background: var(--bg-soft);
            color: var(--text-dark);
            scroll-behavior: smooth;
            padding-top: 80px;
        }

        .page-wrap { 
            padding-top: 0px; 
            padding-bottom: 40px;
        }

        /* Hero Section */
        .parking-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
            margin-top: 0;
            color: #fff;
        }

        .parking-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(255,255,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .parking-hero::after {
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

        .parking-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInDown 0.6s ease 0.1s both;
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
        .parking-container {
            margin-top: -100px;
            position: relative;
            z-index: 10;
        }

        .parking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .parking-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.03);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            transition: var(--transition);
            position: relative;
        }

        .parking-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            border-color: rgba(67, 56, 202, 0.1);
        }

        .parking-card-header {
            padding: 32px 24px 24px;
            text-align: center;
            position: relative;
        }

        .parking-location-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 20px;
        }

        .parking-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .parking-card-body {
            padding: 0 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .coordinate-row {
            display: flex;
            gap: 12px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .coordinate-label {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 50px;
        }

        .coordinate-value {
            font-family: 'Courier New', monospace;
            color: var(--primary);
        }

        .parking-card-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            gap: 12px;
        }

        .btn-map {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-map-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-map-primary:hover {
            transform: scale(1.02);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(67, 56, 202, 0.3);
        }

        .btn-copy {
            padding: 12px;
            background: var(--bg-soft);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-copy:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Info Section */
        .parking-info {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin: 0 20px 40px;
            box-shadow: var(--shadow-sm);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .parking-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .parking-info-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .info-item {
            display: flex;
            gap: 16px;
        }

        .info-item-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4338ca 0%, #818cf8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .info-item-text h4 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .info-item-text p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .parking-hero {
                padding: 60px 0 100px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .parking-grid {
                grid-template-columns: 1fr;
                padding: 0 16px;
            }

            .parking-info {
                padding: 24px;
                margin: 0 16px 32px;
            }
        }

        .copy-success {
            animation: copyNotification 2s ease;
        }

        @keyframes copyNotification {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.8); }
        }
    </style>

    <!-- Hero Section -->
    <section class="parking-hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-geo-alt-fill"></i>&nbsp; Parking Locations
            </div>
            <i class="bi bi-p-square parking-icon"></i>
            <h1 class="hero-title">पार्किंग स्थान</h1>
            <p class="hero-subtitle">आवास निवास के पास उपलब्ध पार्किंग स्थान</p>
        </div>
    </section>

    <!-- Parking Cards Section -->
    <div class="parking-container">
        <div class="parking-grid">
            @foreach($parkingLocations as $location)
            <div class="parking-card">
                <div class="parking-card-header">
                    <div class="parking-location-icon" data-gradient="{{ $location['color'] }}|{{ $location['colorLight'] }}">
                        <i class="bi {{ $location['icon'] }}"></i>
                    </div>
                    <h2 class="parking-card-title">{{ $location['name'] }}</h2>
                </div>

                <div class="parking-card-body">
                </div>

                <div class="parking-card-footer">
                    <a href="{{ $location['mapUrl'] }}" target="_blank" class="btn-map btn-map-primary">
                        <i class="bi bi-map"></i> Google Maps पर देखें
                    </a>
                    <button type="button" class="btn-copy" data-lat="{{ $location['coordinates']['lat'] }}" data-lng="{{ $location['coordinates']['lng'] }}" onclick="copyCoordinates(this.dataset.lat + ', ' + this.dataset.lng, this)">
                        <i class="bi bi-files"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Info Section -->
    <div class="parking-info">
        <h3><i class="bi bi-info-circle-fill" style="color: var(--primary); margin-right: 10px;"></i>महत्वपूर्ण जानकारी</h3>
        <div class="parking-info-content">
            <div class="info-item">
                <div class="info-item-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="info-item-text">
                    <h4>24/7 उपलब्ध</h4>
                    <p>सभी पार्किंग स्थान दिन रात उपलब्ध हैं</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-item-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="info-item-text">
                    <h4>सुरक्षित</h4>
                    <p>सभी स्थान सुरक्षित और निगरानी के तहत हैं</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-item-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="info-item-text">
                    <h4>आसान स्थान</h4>
                    <p>सभी पार्किंग आवास निवास के पास स्थित हैं</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('includes.footer')

</div>

<script>
function copyCoordinates(coordinates, button) {
    navigator.clipboard.writeText(coordinates).then(() => {
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'bi bi-check-circle';
        button.style.background = '#d1fae5';
        
        setTimeout(() => {
            icon.className = originalClass;
            button.style.background = '';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

// Apply gradient styles from data attribute
document.addEventListener('DOMContentLoaded', function() {
    const icons = document.querySelectorAll('[data-gradient]');
    icons.forEach(icon => {
        const [color1, color2] = icon.dataset.gradient.split('|');
        icon.style.background = `linear-gradient(135deg, ${color1} 0%, ${color2} 100%)`;
    });
});
</script>

@endsection
