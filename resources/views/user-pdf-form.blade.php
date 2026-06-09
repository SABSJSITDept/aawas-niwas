@extends('layouts.app')

@section('content')
@include('includes.header')

<!-- Pre-fetch Tailwind via CDN as backup if Vite Tailwind is scoped -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Premium Tailwind UI Wrapper (Breaking out of Bootstrap Container) -->
<div class="vw-100 position-relative" style="left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; background: #f8fafc;">
    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-12 animate-fade-in-down">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-6 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Secure & Verified
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
                    बुकिंग <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">विवरण</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-medium">
                    अपनी फैमिली या ग्रुप बुकिंग की पूरी जानकारी तुरंत प्राप्त करें। सुरक्षित और तेज़ PDF एक्सेस।
                </p>
            </div>

            <!-- Session Error Alert -->
            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl shadow-sm relative animate-fade-in-down" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="block sm:inline font-bold">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="absolute top-1/2 -translate-y-1/2 right-4 text-red-400 hover:text-red-600 transition-colors" onclick="this.parentElement.style.display='none'">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <!-- Main Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6 sm:p-10">
                    <form id="pdfForm" action="{{ route('user.booking.pdf') }}" method="GET">
                        @csrf
                        
                        <!-- Booking Type Selection -->
                        <div class="mb-10">
                            <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">
                                बुकिंग का प्रकार चुनें
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Family Card -->
                                <div class="type-card group relative p-6 rounded-2xl border-2 border-gray-200 bg-white hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-300" data-type="F">
                                    <div class="absolute top-4 right-4 text-gray-200 group-hover:text-blue-500 transition-colors check-icon">
                                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-700">Family Booking</h3>
                                            <p class="text-sm text-gray-500 font-medium mt-1">ID Format: <span class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded font-bold">F-XXX</span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Group Card -->
                                <div class="type-card group relative p-6 rounded-2xl border-2 border-gray-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 cursor-pointer transition-all duration-300" data-type="G">
                                    <div class="absolute top-4 right-4 text-gray-200 group-hover:text-emerald-500 transition-colors check-icon">
                                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-700">Group Booking</h3>
                                            <p class="text-sm text-gray-500 font-medium mt-1">ID Format: <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-bold">G-XXX</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking ID Input -->
                        <div class="mb-10 relative">
                            <label for="booking_id" class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Booking ID या Mobile Number दर्ज करें</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="booking_id" id="booking_id" required
                                       class="block w-full pl-16 pr-14 py-5 text-xl font-black text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-300 placeholder-gray-400"
                                       placeholder="उदाहरण: F-152 या 9876543210" autocomplete="off">
                                <div id="inputStatus" class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none transition-all duration-300">
                                    <!-- Status Icon -->
                                </div>
                            </div>
                            <!-- Validation Error Message -->
                            <p id="errorMessage" class="hidden mt-3 text-sm font-bold text-red-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                <span id="errorText">कृपया सही फॉर्मेट में ID (F-XXX या G-XXX) या 10 अंकों का मोबाइल नंबर दर्ज करें</span>
                            </p>
                        </div>

                        <input type="hidden" name="action" id="actionInput" value="download">

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-6">
                            <button type="submit" onclick="document.getElementById('actionInput').value='view'"
                                    class="relative flex items-center justify-center w-full px-8 py-4 text-lg font-bold text-blue-700 bg-blue-50 border-2 border-blue-200 rounded-2xl hover:bg-blue-100 hover:border-blue-300 hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/30 overflow-hidden group">
                                <svg class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span>View PDF <span class="block text-xs font-medium opacity-70 mt-0.5">ब्राउज़र में देखें</span></span>
                            </button>
                            
                            <button type="submit" onclick="document.getElementById('actionInput').value='download'"
                                    class="relative flex items-center justify-center w-full px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 overflow-hidden group">
                                <svg class="w-6 h-6 mr-3 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Download PDF <span class="block text-xs font-medium text-blue-100 mt-0.5">डिवाइस में सेव करें</span></span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Features Footer -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 sm:px-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm font-bold text-gray-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        Secure & Verified
                    </div>
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Instant Access
                    </div>
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                        Print Ready
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .type-card.active-family {
        border-color: #3b82f6 !important; /* blue-500 */
        background-color: #eff6ff !important; /* blue-50 */
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3) !important;
    }
    
    .type-card.active-group {
        border-color: #10b981 !important; /* emerald-500 */
        background-color: #ecfdf5 !important; /* emerald-50 */
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3) !important;
    }
    
    .type-card.active-family .check-icon, 
    .type-card.active-group .check-icon {
        color: currentColor !important;
    }
    .type-card.active-family .check-icon { color: #3b82f6 !important; }
    .type-card.active-group .check-icon { color: #10b981 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pdfForm');
    const bookingIdInput = document.getElementById('booking_id');
    const inputStatus = document.getElementById('inputStatus');
    const errorMessage = document.getElementById('errorMessage');
    const typeCards = document.querySelectorAll('.type-card');
    
    // Initial state check
    if(bookingIdInput.value) validateInput();

    // Type Card Click Handler
    typeCards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type; // 'F' or 'G'
            bookingIdInput.value = type + '-';
            bookingIdInput.focus();
            validateInput();
        });
    });
    
    // Input Validation & Auto-Formatting
    bookingIdInput.addEventListener('input', validateInput);
    
    function validateInput() {
        let value = bookingIdInput.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        
        // Auto-add hyphen
        if (value.length === 1 && /[FVG]/.test(value)) {
            value += '-';
        }
        
        bookingIdInput.value = value;
        
        // Reset Visuals
        typeCards.forEach(c => c.classList.remove('active-family', 'active-group'));
        inputStatus.innerHTML = '';
        bookingIdInput.classList.remove('border-red-400', 'border-emerald-400', 'border-blue-400');
        errorMessage.classList.add('hidden');
        
        // Match Pattern
        if (/^\d+$/.test(value)) {
            // Mobile Number logic
            if (value.length === 10) {
                showSuccessStatus('text-blue-500');
                bookingIdInput.classList.add('border-blue-400');
            } else if (value.length > 10) {
                showErrorStatus();
            }
        } else if (value.startsWith('F-')) {
            document.querySelector('[data-type="F"]').classList.add('active-family');
            if (/^F-\d+$/.test(value)) {
                showSuccessStatus('text-blue-500');
                bookingIdInput.classList.add('border-blue-400');
            } else if (value.length > 2) {
                showErrorStatus();
            }
        } else if (value.startsWith('G-')) {
            document.querySelector('[data-type="G"]').classList.add('active-group');
            if (/^G-\d+$/.test(value)) {
                showSuccessStatus('text-emerald-500');
                bookingIdInput.classList.add('border-emerald-400');
            } else if (value.length > 2) {
                showErrorStatus();
            }
        } else if (value.length > 0) {
            showErrorStatus();
        }
    }
    
    function showSuccessStatus(colorClass) {
        inputStatus.innerHTML = `<svg class="w-8 h-8 ${colorClass} animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>`;
    }
    
    function showErrorStatus() {
        bookingIdInput.classList.add('border-red-400');
        inputStatus.innerHTML = `<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    }
    
    // Form Submission Validation
    form.addEventListener('submit', function(e) {
        const bookingId = bookingIdInput.value.trim();
        
        if (!(/^[FVG]-\d+$/i.test(bookingId)) && !(/^\d{10}$/.test(bookingId))) {
            e.preventDefault();
            bookingIdInput.classList.add('border-red-500');
            errorMessage.classList.remove('hidden');
            bookingIdInput.focus();
        }
    });
});
</script>
@endsection
