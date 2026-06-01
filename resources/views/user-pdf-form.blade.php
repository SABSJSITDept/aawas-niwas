@extends('layouts.app')

@section('content')

@include('includes.header')

<!-- Professional Hero Section -->
<div class="pdf-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <div class="hero-content">
                    <div class="hero-badge mb-3">
                        <i class="bi bi-patch-check-fill me-2"></i>Secure & Verified
                    </div>
                    <h1 class="display-4 fw-bold mb-3">बुकिंग विवरण</h1>
                    <p class="lead mb-4">अपनी बुकिंग की पूरी जानकारी तुरंत प्राप्त करें। PDF में देखें या डाउनलोड करें।</p>
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="bi bi-lightning-charge-fill"></i>
                            <span>Instant Access</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check"></i>
                            <span>Secure</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-download"></i>
                            <span>Easy Download</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-n5 mb-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Professional Card -->
            <div class="professional-card">
                <div class="card-header-modern">
                    <div class="header-icon-wrapper">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <h4 class="header-title">PDF Document Access</h4>
                    <p class="header-subtitle">अपनी बुकिंग ID से पूरी जानकारी प्राप्त करें</p>
                </div>
                <div class="card-body p-5">
                    <!-- Professional Form -->
                    <form id="pdfForm" action="{{ route('user.booking.pdf') }}" method="GET">
                        @csrf

                        <!-- Booking Type Selection -->
                        <div class="booking-type-selector mb-5">
                            <label class="form-label-modern mb-3">बुकिंग टाइप चुनें</label>
                            <div class="row g-3">
                                {{-- <div class="col-md-4">
                                    <div class="type-card" data-type="F">
                                        <div class="type-icon family">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <h6 class="mb-1">Family</h6>
                                        <small class="text-muted">F-XXX</small>
                                    </div>
                                </div> --}}
                               
                                <div class="col-md-4">
                                    <div class="type-card" data-type="G">
                                        <div class="type-icon group">
                                            <i class="bi bi-diagram-3-fill"></i>
                                        </div>
                                        <h6 class="mb-1">Group</h6>
                                        <small class="text-muted">G-XXX</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Booking ID Input -->
                        <div class="form-group-modern mb-5">
                            <label for="booking_id" class="form-label-modern">
                                <i class="bi bi-hash me-2"></i>Enter Booking ID
                            </label>
                            <div class="input-wrapper-modern">
                                <div class="input-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <input type="text" name="booking_id" id="booking_id" class="form-control-modern" required 
                                       placeholder="जैसे: F-216, V-123, G-456"
                                       autocomplete="off">
                                <div class="input-status" id="inputStatus"></div>
                            </div>
                            <div class="form-hint">
                                <i class="bi bi-info-circle me-1"></i>
                                अपनी बुकिंग ID टाइप या पेस्ट करें (Format: F-216)
                            </div>
                        </div>

                        <input type="hidden" name="action" id="actionInput" value="download">

                        <!-- Professional Action Buttons -->
                                <button type="submit" class="btn-modern btn-modern-outline w-100"
                                    onclick="document.getElementById('actionInput').value='view'">
                                    <div class="btn-icon">
                                        <i class="bi bi-eye-fill"></i>
                                    </div>
                                    <div class="btn-content">
                                        <span class="btn-title">View PDF</span>
                                        <span class="btn-subtitle">ब्राउज़र में देखें</span>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn-modern btn-modern-primary w-100"
                                    onclick="document.getElementById('actionInput').value='download'">
                                    <div class="btn-icon">
                                        <i class="bi bi-download"></i>
                                    </div>
                                    <div class="btn-content">
                                        <span class="btn-title">Download PDF</span>
                                        <span class="btn-subtitle">डिवाइस में सेव करें</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Professional Info Cards -->
                    <div class="info-cards-grid">
                        <div class="info-card">
                            <div class="info-icon blue">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div class="info-content">
                                <h6>Instant Access</h6>
                                <p>तुरंत अपनी बुकिंग देखें</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon green">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="info-content">
                                <h6>Secure & Safe</h6>
                                <p>पूरी तरह सुरक्षित सिस्टम</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon purple">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>
                            <div class="info-content">
                                <h6>High Quality PDF</h6>
                                <p>Professional PDF format</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="tips-section">
                        <div class="tips-header">
                            <i class="bi bi-lightbulb-fill"></i>
                            <span>महत्वपूर्ण जानकारी</span>
                        </div>
                        <div class="tips-content">
                            <div class="tip-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Booking ID हमेशा capital letters में होती है</span>
                            </div>
                            <div class="tip-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Format: Letter-Number (जैसे F-216, V-123)</span>
                            </div>
                            <div class="tip-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Download करने से पहले View option से चेक कर सकते हैं</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Feature Cards -->
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper orange">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5>24/7 Access</h5>
                        <p>कभी भी अपनी बुकिंग देखें</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper green">
                            <i class="bi bi-printer"></i>
                        </div>
                        <h5>Print Ready</h5>
                        <p>Direct print कर सकते हैं</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

<!-- Professional JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pdfForm');
    const bookingIdInput = document.getElementById('booking_id');
    const inputStatus = document.getElementById('inputStatus');
    const typeCards = document.querySelectorAll('.type-card');
    
    // Type Card Selection
    typeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all
            typeCards.forEach(c => c.classList.remove('active'));
            // Add active class to clicked
            this.classList.add('active');
            
            // Auto-fill booking type
            const type = this.dataset.type;
            if (bookingIdInput.value === '' || bookingIdInput.value.length <= 2) {
                bookingIdInput.value = type + '-';
                bookingIdInput.focus();
                updateInputStatus();
            }
        });
    });
    
    // Set G as default booking type on page load
    const gCard = document.querySelector('[data-type="G"]');
    if (gCard) {
        gCard.classList.add('active');
        bookingIdInput.value = 'G-';
    }
    
    // Enhanced booking ID validation with visual feedback
    bookingIdInput.addEventListener('input', function() {
        updateInputStatus();
    });
    
    function updateInputStatus() {
        const value = bookingIdInput.value.trim().toUpperCase();
        bookingIdInput.value = value;
        
        // Reset status
        inputStatus.innerHTML = '';
        bookingIdInput.style.borderColor = '#e2e8f0';
        
        if (value.startsWith('F-')) {
            if (/^F-\d+$/.test(value)) {
                inputStatus.innerHTML = '<i class="bi bi-check-circle-fill" style="color: #10b981;"></i>';
                bookingIdInput.style.borderColor = '#10b981';
                activateTypeCard('F');
            } else if (value.length > 2) {
                inputStatus.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color: #f59e0b;"></i>';
                bookingIdInput.style.borderColor = '#f59e0b';
            }
        } else if (value.startsWith('V-')) {
            if (/^V-\d+$/.test(value)) {
                inputStatus.innerHTML = '<i class="bi bi-check-circle-fill" style="color: #10b981;"></i>';
                bookingIdInput.style.borderColor = '#10b981';
                activateTypeCard('V');
            } else if (value.length > 2) {
                inputStatus.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color: #f59e0b;"></i>';
                bookingIdInput.style.borderColor = '#f59e0b';
            }
        } else if (value.startsWith('G-')) {
            if (/^G-\d+$/.test(value)) {
                inputStatus.innerHTML = '<i class="bi bi-check-circle-fill" style="color: #10b981;"></i>';
                bookingIdInput.style.borderColor = '#10b981';
                activateTypeCard('G');
            } else if (value.length > 2) {
                inputStatus.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color: #f59e0b;"></i>';
                bookingIdInput.style.borderColor = '#f59e0b';
            }
        } else if (value.length > 0) {
            inputStatus.innerHTML = '<i class="bi bi-x-circle-fill" style="color: #ef4444;"></i>';
            bookingIdInput.style.borderColor = '#ef4444';
        }
    }
    
    function activateTypeCard(type) {
        typeCards.forEach(card => {
            if (card.dataset.type === type) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }
    
    // Enhanced form submission with loading state and validation
    form.addEventListener('submit', function(e) {
        const submitButton = e.submitter;
        const originalHTML = submitButton.innerHTML;
        const bookingId = bookingIdInput.value.trim();
        
        // Basic validation
        if (!bookingId) {
            e.preventDefault();
            showAlert('कृपया Booking ID दर्ज करें', 'warning');
            bookingIdInput.focus();
            return;
        }
        
        // Check booking ID format
        if (!(/^[FVG]-\d+$/i.test(bookingId))) {
            e.preventDefault();
            showAlert('कृपया सही फॉर्मेट में Booking ID दर्ज करें (जैसे: F-216, V-123, G-456)', 'danger');
            bookingIdInput.focus();
            return;
        }
        
        // Add loading state
        submitButton.disabled = true;
        const isView = submitButton.onclick && submitButton.onclick.toString().includes('view');
        const actionText = isView ? 'देखा जा रहा है' : 'डाउनलोड हो रहा है';
        
        submitButton.innerHTML = `
            <div class="btn-icon">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="btn-content">
                <span class="btn-title">${actionText}...</span>
                <span class="btn-subtitle">कृपया प्रतीक्षा करें</span>
            </div>
        `;
        
        // Reset button after 10 seconds (fallback)
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalHTML;
        }, 10000);
    });
    
    // Auto-format booking ID as user types
    bookingIdInput.addEventListener('keyup', function(e) {
        let value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        
        // Auto-add hyphen after letter
        if (value.length === 1 && /[FVG]/.test(value)) {
            value += '-';
        }
        
        // Limit length
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        this.value = value;
        updateInputStatus();
    });
    
    // Paste handling
    bookingIdInput.addEventListener('paste', function(e) {
        setTimeout(() => {
            updateInputStatus();
        }, 10);
    });
    
    // Show alert function
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlert = document.querySelector('.dynamic-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show dynamic-alert`;
        alertDiv.style.marginBottom = '20px';
        
        let icon = '';
        switch(type) {
            case 'success':
                icon = '<i class="bi bi-check-circle-fill me-2"></i>';
                break;
            case 'danger':
                icon = '<i class="bi bi-x-circle-fill me-2"></i>';
                break;
            case 'warning':
                icon = '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
                break;
            default:
                icon = '<i class="bi bi-info-circle-fill me-2"></i>';
        }
        
        alertDiv.innerHTML = `
            ${icon}${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert before form
        form.parentNode.insertBefore(alertDiv, form);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 5000);
    }
    
    // Add smooth scroll animation
    window.addEventListener('load', function() {
        document.body.style.opacity = '0';
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.5s ease';
            document.body.style.opacity = '1';
        }, 100);
    });
});
</script>

<!-- Professional Modern CSS -->
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --orange-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --green-gradient: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
}

/* Hero Section */
.pdf-hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0 120px;
    position: relative;
    overflow: hidden;
}

.pdf-hero-section::before {
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
}

.hero-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    animation: fadeInDown 0.6s ease;
}

.hero-features {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    font-weight: 500;
}

.feature-item i {
    font-size: 20px;
}

/* Professional Card */
.professional-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    overflow: hidden;
    position: relative;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

/* Booking Type Selector */
.booking-type-selector {
    animation: fadeIn 0.8s ease;
}

.form-label-modern {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    display: block;
}

.type-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.type-card:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
}

.type-card.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.type-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 16px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.type-icon.family {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.type-icon.vip {
    background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
    color: white;
}

.type-icon.group {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.type-card.active .type-icon {
    background: rgba(255,255,255,0.2);
}

/* Modern Input */
.form-group-modern {
    animation: fadeIn 1s ease;
}

.input-wrapper-modern {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 20px;
    z-index: 2;
    color: #a0aec0;
    font-size: 20px;
    transition: all 0.3s ease;
}

.form-control-modern {
    width: 100%;
    padding: 18px 60px 18px 60px;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control-modern:focus ~ .input-icon {
    color: #667eea;
}

.input-status {
    position: absolute;
    right: 20px;
    font-size: 24px;
    transition: all 0.3s ease;
}

.form-hint {
    margin-top: 12px;
    color: #718096;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Professional Buttons */
.btn-modern {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border: none;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-modern::before {
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

.btn-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-modern-outline {
    background: white;
    border: 2px solid #667eea;
    color: #667eea;
}

.btn-modern-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.btn-modern-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-modern-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
}

.btn-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.btn-modern-outline .btn-icon {
    background: rgba(102, 126, 234, 0.1);
}

.btn-content {
    text-align: left;
    flex: 1;
}

.btn-title {
    display: block;
    font-size: 16px;
    margin-bottom: 2px;
}

.btn-subtitle {
    display: block;
    font-size: 12px;
    opacity: 0.8;
    font-weight: 400;
}

/* Info Cards Grid */
.info-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid #e2e8f0;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.info-card:hover {
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transform: translateY(-3px);
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    flex-shrink: 0;
}

.info-icon.blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.info-icon.green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.info-icon.purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.info-content h6 {
    margin: 0 0 4px;
    font-size: 14px;
    font-weight: 600;
    color: #2d3748;
}

.info-content p {
    margin: 0;
    font-size: 12px;
    color: #718096;
}

/* Tips Section */
.tips-section {
    margin-top: 40px;
    padding: 24px;
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 20%, #ff9a9e 40%, #fad0c4 100%);
    border-radius: 16px;
}

.tips-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 16px;
}

.tips-header i {
    font-size: 24px;
    color: #f59e0b;
}

.tips-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.tip-item {
    display: flex;
    align-items: start;
    gap: 12px;
    padding: 12px;
    background: rgba(255,255,255,0.7);
    border-radius: 12px;
    font-size: 14px;
    color: #2d3748;
}

.tip-item i {
    color: #10b981;
    font-size: 18px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Feature Cards */
.feature-card {
    background: white;
    padding: 32px 24px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.feature-icon-wrapper {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
}

.feature-icon-wrapper.orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.feature-icon-wrapper.blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.feature-icon-wrapper.green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.feature-card h5 {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.feature-card p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

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

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading {
    animation: pulse 1.5s infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .pdf-hero-section {
        padding: 60px 0 100px;
    }
    
    .hero-features {
        gap: 15px;
    }
    
    .feature-item {
        padding: 10px 16px;
        font-size: 14px;
    }
    
    .professional-card {
        border-radius: 16px;
    }
    
    .card-header-modern {
        padding: 30px 20px 20px;
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
    
    .type-card {
        padding: 16px;
    }
    
    .type-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .info-cards-grid {
        grid-template-columns: 1fr;
    }
    
    .mt-n5 {
        margin-top: -3rem !important;
    }
}

/* Alert Styling */
.alert {
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    border-left: 4px solid;
    animation: fadeInDown 0.4s ease;
}

.alert-info {
    background: #e0f2fe;
    border-left-color: #0ea5e9;
    color: #075985;
}

.alert-warning {
    background: #fef3c7;
    border-left-color: #f59e0b;
    color: #92400e;
}

.alert-danger {
    background: #fee2e2;
    border-left-color: #ef4444;
    color: #991b1b;
}

.alert-success {
    background: #d1fae5;
    border-left-color: #10b981;
    color: #065f46;
}
</style>

@endsection
