<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form - आवास निवास</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --blue-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Hero Section */
        .feedback-hero {
            background: var(--primary-gradient);
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }

        .feedback-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .hero-badge {
            display: inline-block;
            padding: 10px 24px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
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

        /* Main Container */
        .feedback-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .feedback-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }

        .card-header-modern {
            text-align: center;
            padding: 40px 30px 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
        }

        .header-icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: bounce 2s infinite;
        }

        .header-icon-wrapper i {
            font-size: 36px;
            color: white;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .header-subtitle {
            color: #718096;
            font-size: 16px;
            margin: 0;
        }

        /* Form Styling */
        .form-body {
            padding: 40px;
        }

        .form-group-modern {
            margin-bottom: 28px;
        }

        .form-label-modern {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label-modern i {
            font-size: 20px;
            color: #667eea;
        }

        .input-wrapper-modern {
            position: relative;
        }

        .form-control-modern {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control-modern:valid {
            border-color: #10b981;
        }

        textarea.form-control-modern {
            resize: vertical;
            min-height: 140px;
        }

        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e0;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control-modern:focus ~ .input-icon {
            color: #667eea;
        }

        .form-control-modern:valid ~ .input-icon {
            color: #10b981;
        }

        .form-hint {
            margin-top: 8px;
            color: #718096;
            font-size: 13px;
        }

        /* Submit Button */
        .btn-submit-modern {
            width: 100%;
            padding: 18px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-submit-modern:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-submit-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-submit-modern i {
            font-size: 22px;
        }

        /* Info Cards */
        .info-section {
            padding: 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .info-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
        }

        .info-icon.blue {
            background: var(--blue-gradient);
        }

        .info-icon.green {
            background: var(--success-gradient);
        }

        .info-icon.pink {
            background: var(--secondary-gradient);
        }

        .info-card h6 {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .info-card p {
            font-size: 13px;
            color: #718096;
            margin: 0;
        }

        /* Alert Styling */
        .alert-modern {
            border: none;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: slideInDown 0.5s ease;
        }

        .alert-success-modern {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .alert-modern i {
            font-size: 28px;
            color: #10b981;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            display: block;
            font-size: 16px;
            margin-bottom: 4px;
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

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Loading State */
        .btn-submit-modern:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border-sm {
            width: 20px;
            height: 20px;
            border-width: 2px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .feedback-hero {
                padding: 60px 0 100px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .feedback-container {
                margin-top: -60px;
            }

            .card-header-modern,
            .form-body,
            .info-section {
                padding: 24px;
            }

            .header-icon-wrapper {
                width: 60px;
                height: 60px;
            }

            .header-icon-wrapper i {
                font-size: 28px;
            }

            .header-title {
                font-size: 22px;
            }

            .info-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Character Counter */
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 8px;
        }

        .char-counter.warning {
            color: #f59e0b;
        }

        .char-counter.danger {
            color: #ef4444;
        }
    </style>
</head>
<body>

    {{-- ✅ Include Header --}}
    @include('includes.header')

    <!-- Hero Section -->
    <div class="feedback-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="bi bi-chat-dots-fill me-2"></i>We Value Your Opinion
                </div>
                <h1 class="hero-title">💬 Feedback Form</h1>
                <p class="hero-subtitle">अपनी राय साझा करें और हमें बेहतर बनाने में मदद करें</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container feedback-container mb-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="feedback-card">
                    
                    <!-- Card Header -->
                    <div class="card-header-modern">
                        <div class="header-icon-wrapper">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h4 class="header-title">Share Your Feedback</h4>
                        <p class="header-subtitle">आपकी प्रतिक्रिया हमारे लिए महत्वपूर्ण है</p>
                    </div>

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="alert-modern alert-success-modern">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="alert-content">
                                <strong>सफलता!</strong>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form Body -->
                    <div class="form-body">
                        <form method="POST" action="{{ route('feedback.submit') }}" id="feedbackForm">
                            @csrf

                            <!-- Name Field -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-person-fill"></i>
                                    पूरा नाम
                                </label>
                                <div class="input-wrapper-modern">
                                    <input type="text" 
                                           name="name" 
                                           class="form-control-modern" 
                                           placeholder="अपना पूरा नाम दर्ज करें" 
                                           required
                                           minlength="3">
                                    <i class="bi bi-check-circle-fill input-icon"></i>
                                </div>
                                <div class="form-hint">कम से कम 3 अक्षर</div>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-envelope-fill"></i>
                                    ईमेल पता
                                </label>
                                <div class="input-wrapper-modern">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control-modern" 
                                           placeholder="example@email.com" 
                                           required>
                                    <i class="bi bi-check-circle-fill input-icon"></i>
                                </div>
                                <div class="form-hint">हम आपको confirmation email भेजेंगे</div>
                            </div>

                            <!-- Phone Field -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-telephone-fill"></i>
                                    मोबाइल नंबर
                                </label>
                                <div class="input-wrapper-modern">
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control-modern" 
                                           placeholder="10 अंकों का मोबाइल नंबर" 
                                           pattern="[0-9]{10}" 
                                           maxlength="10"
                                           required>
                                    <i class="bi bi-check-circle-fill input-icon"></i>
                                </div>
                                <div class="form-hint">केवल 10 अंक (बिना +91 के)</div>
                            </div>

                            <!-- Message Field -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-chat-text-fill"></i>
                                    आपका संदेश / सुझाव
                                </label>
                                <div class="input-wrapper-modern">
                                    <textarea name="message" 
                                              class="form-control-modern" 
                                              placeholder="अपने अनुभव, सुझाव या शिकायत यहाँ लिखें..."
                                              required
                                              minlength="10"
                                              maxlength="1000"
                                              id="messageArea"></textarea>
                                </div>
                                <div class="char-counter" id="charCounter">0 / 1000 characters</div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-submit-modern" id="submitBtn">
                                <i class="bi bi-send-fill"></i>
                                <span>सबमिट करें</span>
                            </button>

                        </form>
                    </div>

                    <!-- Info Section -->
                    <div class="info-section">
                        <div class="info-cards-grid">
                            <div class="info-card">
                                <div class="info-icon blue">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <h6>Quick Response</h6>
                                <p>24 घंटे में जवाब</p>
                            </div>
                            <div class="info-card">
                                <div class="info-icon green">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h6>100% Secure</h6>
                                <p>आपकी जानकारी सुरक्षित</p>
                            </div>
                            <div class="info-card">
                                <div class="info-icon pink">
                                    <i class="bi bi-envelope-heart"></i>
                                </div>
                                <h6>Email Confirmation</h6>
                                <p>Instant confirmation</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Include Footer --}}
    @include('includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Professional JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('feedbackForm');
            const submitBtn = document.getElementById('submitBtn');
            const messageArea = document.getElementById('messageArea');
            const charCounter = document.getElementById('charCounter');
            const phoneInput = document.querySelector('input[name="phone"]');
            
            // Character Counter for Message
            messageArea.addEventListener('input', function() {
                const length = this.value.length;
                const maxLength = 1000;
                charCounter.textContent = `${length} / ${maxLength} characters`;
                
                // Color coding
                if (length > maxLength * 0.9) {
                    charCounter.classList.add('danger');
                    charCounter.classList.remove('warning');
                } else if (length > maxLength * 0.7) {
                    charCounter.classList.add('warning');
                    charCounter.classList.remove('danger');
                } else {
                    charCounter.classList.remove('warning', 'danger');
                }
            });
            
            // Phone Number Validation
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
            
            // Real-time validation feedback
            const inputs = form.querySelectorAll('.form-control-modern');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.validity.valid && this.value.length > 0) {
                        this.style.borderColor = '#10b981';
                    } else if (this.value.length > 0) {
                        this.style.borderColor = '#ef4444';
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.validity.valid) {
                        this.style.borderColor = '#10b981';
                    }
                });
            });
            
            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    showValidationErrors();
                    return;
                }
                
                // Add loading state
                submitBtn.disabled = true;
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>सबमिट हो रहा है...</span>
                `;
                
                // Reset button after 10 seconds (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                }, 10000);
            });
            
            // Show validation errors
            function showValidationErrors() {
                inputs.forEach(input => {
                    if (!input.validity.valid) {
                        input.style.borderColor = '#ef4444';
                        input.focus();
                    }
                });
            }
            
            // Auto-dismiss success alert
            const successAlert = document.querySelector('.alert-modern');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.animation = 'slideInDown 0.5s ease reverse';
                    setTimeout(() => {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }
            
            // Smooth scroll to form on error
            if (document.querySelector('.alert-modern')) {
                document.querySelector('.feedback-card').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
            
            // Add smooth animations on load
            window.addEventListener('load', function() {
                document.body.style.opacity = '0';
                setTimeout(() => {
                    document.body.style.transition = 'opacity 0.5s ease';
                    document.body.style.opacity = '1';
                }, 100);
            });
            
            // Name field - capitalize first letter
            const nameInput = document.querySelector('input[name="name"]');
            nameInput.addEventListener('blur', function() {
                this.value = this.value.split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ');
            });
            
            // Email field - lowercase
            const emailInput = document.querySelector('input[name="email"]');
            emailInput.addEventListener('blur', function() {
                this.value = this.value.toLowerCase().trim();
            });
        });
    </script>
</body>
</html>
