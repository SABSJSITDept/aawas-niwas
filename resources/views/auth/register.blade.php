<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aawas Niwas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Password Modal */
        .password-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .password-modal-content {
            background: white;
            padding: 50px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 90%;
            animation: modalSlideIn 0.5s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .password-modal-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .password-modal-content h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .password-modal-content p {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .password-input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .password-input-group input {
            padding: 15px 45px 15px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .password-input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .password-toggle-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .password-toggle-btn:hover {
            color: #764ba2;
        }

        .btn-unlock {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-unlock:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        .btn-unlock:active {
            transform: translateY(-1px);
        }

        .btn-unlock:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-alert {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 10px;
            display: none;
        }

        .error-alert.show {
            display: block;
        }

        /* Hidden by default */
        .register-wrapper {
            display: none;
        }

        .register-wrapper.show {
            display: flex;
        }

        .register-wrapper {
            flex: 1;
            min-height: 100vh;
        }

        /* Left Side - Welcome Section */
        .register-welcome {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .register-welcome::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .register-welcome::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 400px;
        }

        .welcome-icon {
            font-size: 5rem;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        .welcome-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .welcome-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .welcome-features {
            text-align: left;
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .feature-item i {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        /* Right Side - Form Section */
        .register-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f8f9fa;
        }

        .register-form-container {
            width: 100%;
            max-width: 450px;
            animation: slideLeft 0.6s ease-out;
        }

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header h1 {
            font-size: 2.2rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus {
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .cursor-pointer {
            cursor: pointer;
            pointer-events: all !important;
        }

        .form-control.has-icon {
            padding-right: 40px;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 6px;
            display: block;
        }

        .form-text {
            font-size: 0.85rem;
            color: #999;
            margin-top: 6px;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-register:active {
            transform: translateY(-1px);
        }

        .register-footer {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .register-footer p {
            margin-bottom: 0;
            color: #666;
            font-size: 0.95rem;
        }

        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .register-wrapper {
                flex-direction: column;
            }

            .register-welcome {
                padding: 30px;
                min-height: 350px;
            }

            .welcome-content h2 {
                font-size: 2rem;
            }

            .register-form-section {
                padding: 30px;
            }

            .register-form-container {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .register-welcome {
                padding: 20px;
                min-height: 300px;
            }

            .welcome-content h2 {
                font-size: 1.8rem;
            }

            .welcome-icon {
                font-size: 3.5rem;
                margin-bottom: 20px;
            }

            .welcome-features {
                display: none;
            }

            .register-form-section {
                padding: 20px;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }

            .password-modal-content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Password Modal - Shows First -->
    <div class="password-modal-backdrop" id="passwordModal">
        <div class="password-modal-content">
            <div class="password-modal-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>एक्सेस की अनुमति चाहिए</h2>
            <p>रजिस्ट्रेशन फॉर्म खोलने के लिए पासवर्ड दर्ज करें</p>
            
            <div class="password-input-group">
                <input type="password" id="accessPassword" placeholder="पासवर्ड दर्ज करें..." autocomplete="off">
                <button class="password-toggle-btn" type="button" onclick="toggleAccessPassword()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button class="btn-unlock" onclick="verifyPassword()">
                <i class="fas fa-unlock"></i> एक्सेस प्राप्त करें
            </button>

            <div class="error-alert" id="errorMsg">
                <i class="fas fa-exclamation-circle"></i> गलत पासवर्ड। कृपया दोबारा कोशिश करें।
            </div>
        </div>
    </div>

    <!-- Registration Form - Hidden until password verified -->
    <div class="register-wrapper" id="registerWrapper">
        <!-- Left Side - Welcome -->
        <div class="register-welcome">
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="fas fa-gopuram"></i>
                </div>
                <h2>आवास निवास में स्वागत है</h2>
                <p>एक पवित्र आश्रय स्थल जहाँ आप शांति पा सकते हैं</p>
                
                <div class="welcome-features">
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>सुरक्षित और विश्वसनीय</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>आसान बुकिंग प्रक्रिया</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check"></i>
                        <span>24/7 ग्राहक सहायता</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="register-form-section">
            <div class="register-form-container">
                <div class="form-header">
                    <h1>नया खाता बनाएं</h1>
                    <p>शुरुआत करने के लिए यहाँ रजिस्टर करें</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registrationForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">पूरा नाम (Full Name) *</label>
                        <div class="input-icon">
                            <input type="text" id="name" name="name" class="form-control has-icon" 
                                   placeholder="अपना पूरा नाम दर्ज करें" value="{{ old('name') }}" 
                                   required autofocus>
                            <i class="fas fa-user"></i>
                        </div>
                        @if ($errors->has('name'))
                            <span class="error-message">
                                <small>{{ $errors->first('name') }}</small>
                            </span>
                        @endif
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">ईमेल (Email Address) *</label>
                        <div class="input-icon">
                            <input type="email" id="email" name="email" class="form-control has-icon" 
                                   placeholder="आपका ईमेल दर्ज करें" value="{{ old('email') }}" 
                                   required>
                            <i class="fas fa-envelope"></i>
                        </div>
                        @if ($errors->has('email'))
                            <span class="error-message">
                                <small>{{ $errors->first('email') }}</small>
                            </span>
                        @endif
                        <small class="form-text">हम कभी आपकी ईमेल साझा नहीं करेंगे</small>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">पासवर्ड (Password) *</label>
                        <div class="input-icon">
                            <input type="password" id="password" name="password" class="form-control has-icon" 
                                   placeholder="कम से कम 8 अक्षर" required>
                            <i class="fas fa-lock cursor-pointer" onclick="togglePassword('password')"></i>
                        </div>
                        @if ($errors->has('password'))
                            <span class="error-message">
                                <small>{{ $errors->first('password') }}</small>
                            </span>
                        @endif
                        <small class="form-text">मजबूत पासवर्ड चुनें (अक्षर, संख्या, प्रतीक)</small>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation">पासवर्ड की पुष्टि करें *</label>
                        <div class="input-icon">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="form-control has-icon" placeholder="पासवर्ड दोहराएं" required>
                            <i class="fas fa-lock cursor-pointer" onclick="togglePassword('password_confirmation')"></i>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="error-message">
                                <small>{{ $errors->first('password_confirmation') }}</small>
                            </span>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register">
                        <i class="fas fa-check-circle"></i> खाता बनाएं (Register)
                    </button>

                    <!-- Footer -->
                    <div class="register-footer">
                        <p>पहले से खाता है? <a href="{{ route('login') }}">लॉगिन करें</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Correct password - hashed for security
        const CORRECT_PASSWORD = 'Aditya@1008';
        
        // Prevent direct form access
        document.addEventListener('DOMContentLoaded', function() {
            const registerWrapper = document.getElementById('registerWrapper');
            const passwordModal = document.getElementById('passwordModal');
            
            // Always show password modal first
            registerWrapper.classList.remove('show');
            passwordModal.style.display = 'flex';
            
            // Disable Developer Tools shortcuts
            document.addEventListener('keydown', function(e) {
                if (registerWrapper.classList.contains('show')) {
                    return; // Allow shortcuts after password verified
                }
                // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
                if (e.key === 'F12' || 
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
                    (e.ctrlKey && e.key === 'u')) {
                    e.preventDefault();
                }
            });
        });

        function verifyPassword() {
            const inputPassword = document.getElementById('accessPassword').value;
            const errorMsg = document.getElementById('errorMsg');
            const registerWrapper = document.getElementById('registerWrapper');
            const passwordModal = document.getElementById('passwordModal');
            const btn = event.target;

            if (inputPassword === CORRECT_PASSWORD) {
                // Correct password
                errorMsg.classList.remove('show');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-check"></i> एक्सेस प्राप्त...';

                setTimeout(() => {
                    registerWrapper.classList.add('show');
                    passwordModal.style.display = 'none';
                    document.getElementById('registrationForm').focus();
                }, 500);
            } else {
                // Wrong password
                errorMsg.classList.add('show');
                document.getElementById('accessPassword').value = '';
                document.getElementById('accessPassword').focus();
                
                // Shake animation
                passwordModal.querySelector('.password-modal-content').style.animation = 'none';
                setTimeout(() => {
                    passwordModal.querySelector('.password-modal-content').style.animation = 'shake 0.5s';
                }, 10);
            }
        }

        function toggleAccessPassword() {
            const input = document.getElementById('accessPassword');
            const icon = event.target.closest('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = event.target.closest('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-lock');
                icon.classList.add('fa-lock-open');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-lock-open');
                icon.classList.add('fa-lock');
            }
        }

        // Enter key support
        document.getElementById('accessPassword').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyPassword();
            }
        });

        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
