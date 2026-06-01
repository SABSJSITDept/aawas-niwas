@extends('layouts.app')

@section('content')
<div class="page-wrap">
    @include('includes.header')

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --maroon: #6d1b1b;
            --maroon-light: #8b1f1f;
            --bg-soft: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-radius: 16px;
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        html, body {
            font-family: 'Poppins', 'Inter', sans-serif;
            background: var(--bg-soft);
            color: var(--text-dark);
            padding-top: 80px;
        }

        .page-wrap {
            padding-bottom: 40px;
        }

        /* Hero Section */
        .page-hero {
            background: linear-gradient(135deg, rgba(26, 10, 5, 0.9), rgba(109, 27, 27, 0.85)), url('{{ asset("images/bhojanshala-bg.jpg") }}') center/cover no-repeat;
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            border-radius: var(--card-radius);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Content Cards */
        .info-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            height: 100%;
            transition: var(--transition);
            border-top: 4px solid var(--maroon);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .info-icon {
            font-size: 2.5rem;
            color: #eab308; /* Yellow/Orange for food theme */
            margin-bottom: 1rem;
        }

        .timing-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .timing-item:last-child {
            border-bottom: none;
        }

        .timing-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .timing-value {
            color: var(--maroon);
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-up {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>

    <div class="container-fluid px-3 px-sm-4 mt-3">
        
        <!-- Hero Section -->
        <div class="page-hero animate-up">
            <h1 class="hero-title">भोजनशाला</h1>
            <p class="hero-subtitle">दीक्षा महोत्सव में पधारे सभी दर्शनार्थियों के लिए शुद्ध एवं सात्विक भोजन की व्यवस्था।</p>
        </div>

        <div class="row g-4">
            <!-- Timings Card -->
            <div class="col-12 col-md-6 animate-up" style="animation-delay: 0.1s;">
                <div class="info-card">
                    <div class="text-center">
                        <i class="bi bi-clock-history info-icon"></i>
                        <h4 class="fw-bold mb-4">भोजन का समय</h4>
                    </div>
                    
                    <div class="timing-wrapper">
                        <div class="timing-item">
                            <span class="timing-label">नवकारशी (सुबह)</span>
                            <span class="timing-value">07:15 AM - 08:45 AM</span>
                        </div>
                        <div class="timing-item">
                            <span class="timing-label">दोपहर का भोजन</span>
                            <span class="timing-value">11:00 AM - 02:00 PM</span>
                        </div>
                        <div class="timing-item">
                            <span class="timing-label">शाम का भोजन</span>
                            <span class="timing-value">05:00 PM - सूर्यास्त तक</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rules / Notice Card -->
            <div class="col-12 col-md-6 animate-up" style="animation-delay: 0.2s;">
                <div class="info-card" style="border-top-color: var(--primary);">
                    <div class="text-center">
                        <i class="bi bi-info-circle info-icon" style="color: var(--primary);"></i>
                        <h4 class="fw-bold mb-4">आवश्यक सूचनाएँ</h4>
                    </div>
                    
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <span>भोजनशाला में कृपया अनुशासन और शांति बनाए रखें।</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <span>भोजन झूठा न छोड़ें, उतना ही लें जितनी आवश्यकता हो।</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <span>सूर्यास्त के पश्चात भोजनशाला पूर्णतः बंद रहेगी। कृपया समय का ध्यान रखें।</span>
                        </li>
                       
                    </ul>
                </div>
            </div>
            
            <!-- Location / Contact Detail -->
            <div class="col-12 animate-up" style="animation-delay: 0.3s;">
                <div class="info-card" style="border-top-color: #198754;">
                    <div class="row align-items-center">
                        <div class="col-lg-5 text-center text-lg-start mb-4 mb-lg-0">
                            <i class="bi bi-geo-alt-fill info-icon d-inline-block mb-3" style="color: #198754;"></i>
                            <h4 class="fw-bold mb-3">भोजनशाला का स्थान</h4>
                            <p class="text-muted mb-4 fs-5">आयोजन स्थल (Dummy Location)</p>
                            
                            <a href="#" 
                               target="_blank" 
                               class="btn btn-success rounded-pill px-4 py-2" 
                               style="background-color: #198754; border: none; font-weight: 500; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);">
                               <i class="bi bi-map me-2"></i> Google Maps पर देखें
                            </a>
                        </div>
                        <div class="col-lg-7">
                            <div class="map-container" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #eee;">
                                <iframe 
                                    src="https://maps.google.com/maps?q=Dummy+Location&amp;t=&amp;z=15&amp;ie=UTF8&amp;iwloc=&amp;output=embed" 
                                    width="100%" 
                                    height="300" 
                                    style="border:0; display: block;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Footer include -->
    <div class="mt-5">
        @includeIf('includes.footer')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
