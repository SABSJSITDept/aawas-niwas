<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-gradient-start: #6366f1;
            --bg-gradient-end: #8b5cf6;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left Side - Branding */
        .brand-side {
            flex: 1;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .brand-side::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: movePattern 20s linear infinite;
        }

        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .brand-logo {
            max-width: 180px;
            max-height: 180px;
            margin-bottom: 2rem;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .brand-subtitle {
            font-size: 1.125rem;
            opacity: 0.95;
            line-height: 1.6;
            max-width: 400px;
            margin: 0 auto;
        }

        .decorative-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: pulse 4s ease-in-out infinite;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: 1s;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.1); opacity: 0.15; }
        }

        /* Right Side - Form */
        .form-side {
            flex: 1;
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .form-container {
            width: 100%;
            max-width: 440px;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: none;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .text-danger {
            font-size: 0.8125rem;
            margin-top: 0.375rem;
            display: block;
        }

        .footer-text {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .footer-text small {
            color: var(--text-secondary);
            font-size: 0.8125rem;
        }

        /* Input Icons */
        .input-group-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        .input-group-icon .form-control {
            padding-left: 3rem;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .form-control {
            padding-right: 3rem;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
            z-index: 10;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .toggle-password:focus {
            outline: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }

            .brand-side {
                min-height: 300px;
                padding: 2rem;
            }

            .brand-logo {
                max-width: 120px;
                max-height: 120px;
            }

            .brand-title {
                font-size: 1.75rem;
            }

            .brand-subtitle {
                font-size: 1rem;
            }

            .form-side {
                padding: 2rem 1.5rem;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .brand-side {
                min-height: 250px;
                padding: 1.5rem;
            }

            .form-side {
                padding: 1.5rem 1rem;
            }

            .form-container {
                max-width: 100%;
            }
        }

        /* Loading State */
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="brand-side">
            <div class="decorative-circles">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
            
            <div class="brand-content">
                <img src="{{ asset('images/logo.png') }}" alt="Your Logo" class="brand-logo">
                <h1 class="brand-title">Welcome to Your App</h1>
                <p class="brand-subtitle">
                    Streamline your workflow and boost productivity with our comprehensive platform
                </p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Sign in to continue</h2>
                    <p>Enter your credentials to access your account</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group-icon">
                            <span class="input-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="you@example.com"
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus>
                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group-icon password-toggle">
                            <span class="input-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                                <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeOffIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Sign In
                        </button>
                    </div>

                    <div class="footer-text">
                        <small>By signing in, you agree to our <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Terms of Service</a> and <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Privacy Policy</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password toggle script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }
    </script>

    <!-- Loading spinner script -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('submitBtn');
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...';
            button.classList.add('btn-loading');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>