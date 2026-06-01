<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | आवास निवास</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --danger: #f56565;
            --light-bg: #f8fafc;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #5a3f9e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* Main Container - Landscape Layout */
        .login-container {
            width: 95%;
            max-width: 1300px;
            height: 90vh;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        /* Left Side - Info/Brand */
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #5a3f9e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -40%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }

        /* Right Side - Form */
        .login-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 50px;
            background: white;
            position: relative;
            z-index: 2;
        }

        .left-content {
            position: relative;
            z-index: 3;
            text-align: center;
            animation: fadeInLeft 0.8s ease-out 0.2s backwards;
        }

        .left-content .logo {
            max-height: 120px;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));
        }

        .left-content h2 {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 0 15px 0;
            letter-spacing: -0.5px;
        }

        .left-content p {
            color: rgba(255,255,255,0.9);
            font-size: 1.05rem;
            line-height: 1.7;
            margin: 0;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin-top: 45px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255,255,255,0.95);
            font-size: 0.98rem;
            font-weight: 500;
            animation: slideInLeft 0.6s ease-out backwards;
        }

        .feature-item:nth-child(1) { animation-delay: 0.3s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.5s; }

        .feature-item i {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover i {
            background: rgba(255,255,255,0.35);
            transform: scale(1.1);
        }

        /* Right Side - Form */
        .form-wrapper {
            width: 100%;
            max-width: 380px;
            animation: fadeInRight 0.8s ease-out 0.2s backwards;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            font-size: 0.95rem;
            color: #718096;
            margin-bottom: 35px;
            text-align: center;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 22px;
            animation: fadeIn 0.8s ease-out;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: #667eea;
            font-size: 1rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            color: #1a202c;
        }

        .form-control::placeholder {
            color: #cbd5e0;
        }

        /* Error Messages */
        .error-message {
            display: block;
            color: #f56565;
            font-size: 0.85rem;
            margin-top: 8px;
            font-weight: 500;
        }

        /* Submit Button */
        .login-btn {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .login-btn:hover::before {
            width: 400px;
            height: 400px;
        }

        /* Remember Me & Forgot Password */
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .remember-me label {
            font-size: 0.95rem;
            color: #2d3748;
            font-weight: 500;
            cursor: pointer;
            margin: 0;
        }

        .forgot-password {
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 25px;
            padding: 14px 18px;
            font-size: 0.95rem;
            font-weight: 500;
            animation: slideDown 0.5s ease-out;
        }

        .alert-danger {
            background: #fff5f5;
            color: #c53030;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-danger i {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Sign Up Link */
        .signup-link {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            color: #764ba2;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(40px);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-container {
                max-width: 100%;
                height: auto;
                min-height: 100vh;
            }

            .login-left,
            .login-right {
                padding: 50px 40px;
            }

            .left-content h2 {
                font-size: 2rem;
            }

            .feature-list {
                margin-top: 35px;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .login-left {
                min-height: 300px;
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .left-content h2 {
                font-size: 1.8rem;
            }

            .left-content p {
                font-size: 0.95rem;
            }

            .feature-list {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                margin-top: 30px;
                text-align: center;
            }

            .feature-item {
                flex-direction: column;
                gap: 8px;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .form-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                height: auto;
                min-height: 100vh;
                border-radius: 20px;
            }

            .login-left {
                min-height: 250px;
                padding: 30px 20px;
            }

            .login-right {
                padding: 30px 20px;
            }

            .left-content h2 {
                font-size: 1.5rem;
            }

            .left-content p {
                font-size: 0.9rem;
            }

            .left-content .logo {
                max-height: 100px;
            }

            .feature-list {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .feature-item {
                font-size: 0.85rem;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 11px 14px;
            }

            .login-btn {
                padding: 12px 20px;
                font-size: 0.95rem;
            }

            .login-options {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 20px;
            }

            .forgot-password {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Brand & Features -->
        <div class="login-left">
            <div class="left-content">
                <img src="{{ asset('images/logo_chaturmas01.png') }}" alt="आवास निवास Logo" class="logo">
                <h2>आवास निवास</h2>
                <p>आध्यात्मिक पर्यटन का नया अनुभव</p>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>आसान बुकिंग प्रणाली</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <span>24/7 ग्राहक सहायता</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-lock"></i>
                        <span>100% सुरक्षित लेनदेन</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="form-wrapper">
                <h3 class="form-title">स्वागत है</h3>
                <p class="form-subtitle">अपने खाते में प्रवेश करें</p>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> ईमेल पता
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="आपका ईमेल दर्ज करें"
                            value="{{ old('email') }}"
                            required 
                            autofocus
                        >
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> पासवर्ड
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="अपना पासवर्ड दर्ज करें"
                            required
                        >
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" style="margin: 0;">मुझे याद रखें</label>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                पासवर्ड भूल गए?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> प्रवेश करें
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="signup-link">
                    खाता नहीं है? 
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">यहाँ साइन अप करें</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission animation
            const loginForm = document.getElementById('loginForm');
            const loginBtn = loginForm.querySelector('.login-btn');

            loginForm.addEventListener('submit', function(e) {
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> लोड हो रहा है...';
                loginBtn.disabled = true;
            });

            // Add focus animation to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.style.animation = 'none';
                });
            });
        });
    </script>
</body>
</html>
