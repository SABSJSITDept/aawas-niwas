<!-- Modern Premium Tailwind Header -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<!-- Tailwind Header Wrapper -->
<header id="siteHeader" class="fixed top-0 left-0 right-0 z-[1040] transition-all duration-300 bg-white border-b border-slate-200">
    
    <!-- Top Decorative Line -->
    <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 transition-all duration-300" id="headerContainer">
            
            <!-- Logo & Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-4 group">
                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white flex items-center justify-center p-1 border-2 border-indigo-100 shadow-sm transition-transform duration-500 group-hover:scale-105 group-hover:border-indigo-300">
                    <img src="{{ asset('images/chaturmaslogo.png') }}" alt="Logo" class="w-full h-full object-contain rounded-full" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3069/3069172.png'">
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight tracking-tight font-['Noto_Sans_Devanagari'] transition-colors group-hover:text-indigo-700">
                        समर्पण महोत्सव - 2026
                    </h1>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 font-['Noto_Sans_Devanagari']">
                        बीकानेर, राजस्थान
                    </p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-['Inter'] text-sm font-semibold transition-all duration-300 {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="bi bi-house-door-fill {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-slate-400' }}"></i> <span>Home</span>
                </a>
                
                <a href="{{ route('other_form') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-['Inter'] text-sm font-semibold transition-all duration-300 {{ request()->routeIs('other_form') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="bi bi-calendar-check-fill {{ request()->routeIs('other_form') ? 'text-indigo-600' : 'text-slate-400' }}"></i> <span>Booking</span>
                </a>
                
                <a href="{{ route('location.show') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-['Inter'] text-sm font-semibold transition-all duration-300 {{ request()->routeIs('location.show') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="bi bi-geo-alt-fill {{ request()->routeIs('location.show') ? 'text-indigo-600' : 'text-slate-400' }}"></i> <span>Location</span>
                </a>
                
                <a href="{{ route('feedback.form') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-['Inter'] text-sm font-semibold transition-all duration-300 {{ request()->routeIs('feedback.form') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="bi bi-chat-square-text-fill {{ request()->routeIs('feedback.form') ? 'text-indigo-600' : 'text-slate-400' }}"></i> <span>Feedback</span>
                </a>
            </nav>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobileMenuBtn" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 text-slate-700 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 hover:bg-slate-100 transition-colors">
                <i class="bi bi-list text-2xl leading-none" id="mobileMenuIcon"></i>
            </button>
            
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-xl overflow-hidden transition-all duration-300 opacity-0 invisible" style="max-height: 0;">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Inter'] text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                <div class="w-6 flex justify-center"><i class="bi bi-house-door-fill {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-slate-400' }}"></i></div>
                <span>Home</span>
            </a>
            
            <a href="{{ route('other_form') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Inter'] text-sm font-semibold transition-colors {{ request()->routeIs('other_form') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                <div class="w-6 flex justify-center"><i class="bi bi-calendar-check-fill {{ request()->routeIs('other_form') ? 'text-indigo-600' : 'text-slate-400' }}"></i></div>
                <span>Booking</span>
            </a>
            
            <a href="{{ route('location.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Inter'] text-sm font-semibold transition-colors {{ request()->routeIs('location.show') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                <div class="w-6 flex justify-center"><i class="bi bi-geo-alt-fill {{ request()->routeIs('location.show') ? 'text-indigo-600' : 'text-slate-400' }}"></i></div>
                <span>Location</span>
            </a>
            
            <a href="{{ route('feedback.form') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Inter'] text-sm font-semibold transition-colors {{ request()->routeIs('feedback.form') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                <div class="w-6 flex justify-center"><i class="bi bi-chat-square-text-fill {{ request()->routeIs('feedback.form') ? 'text-indigo-600' : 'text-slate-400' }}"></i></div>
                <span>Feedback</span>
            </a>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('siteHeader');
    const headerContainer = document.getElementById('headerContainer');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuIcon = document.getElementById('mobileMenuIcon');
    let isMenuOpen = false;

    // Body padding offset
    function updatePadding() {
        if (header) {
            document.body.style.paddingTop = header.offsetHeight + 'px';
        }
    }
    
    setTimeout(updatePadding, 150);
    window.addEventListener('resize', updatePadding);

    // Scroll Effect
    window.addEventListener('scroll', function () {
        if (window.scrollY > 20) {
            header.classList.add('shadow-md', 'bg-white/95', 'backdrop-blur-md');
            header.classList.remove('bg-white');
            if(headerContainer) headerContainer.classList.replace('h-20', 'h-16');
        } else {
            header.classList.remove('shadow-md', 'bg-white/95', 'backdrop-blur-md');
            header.classList.add('bg-white');
            if(headerContainer) headerContainer.classList.replace('h-16', 'h-20');
        }
    });

    // Mobile Menu Toggle
    if(mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            isMenuOpen = !isMenuOpen;
            
            if(isMenuOpen) {
                // Open menu
                mobileMenu.classList.remove('opacity-0', 'invisible');
                mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px";
                mobileMenuIcon.classList.replace('bi-list', 'bi-x-lg');
            } else {
                // Close menu
                mobileMenu.style.maxHeight = "0";
                mobileMenu.classList.add('opacity-0');
                setTimeout(() => {
                    if(!isMenuOpen) mobileMenu.classList.add('invisible');
                }, 300);
                mobileMenuIcon.classList.replace('bi-x-lg', 'bi-list');
            }
        });
    }
});
</script>
