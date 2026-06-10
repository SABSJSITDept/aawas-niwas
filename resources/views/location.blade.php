@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 pb-12 font-['Inter']">

    @include('includes.header')

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- HERO SECTION -->
    <div class="relative bg-slate-900 overflow-hidden py-12 lg:py-16 mb-8 lg:mb-12">
        <!-- Glow Effects -->
        <div class="absolute w-[500px] h-[500px] bg-emerald-500/20 rounded-full blur-[100px] -top-32 -left-32 pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] bg-teal-500/15 rounded-full blur-[100px] -bottom-32 -right-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-medium px-4 py-1.5 rounded-full mb-5 font-['Noto_Sans_Devanagari']">
                <i class="bi bi-geo-alt-fill"></i> Find Us Here
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 font-['Noto_Sans_Devanagari']">
                📍 हमारी Location
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-['Noto_Sans_Devanagari']">
                {{ $settings['location_title'] ?? 'Seva Sadan Chabali Ghati, Bikaner' }} में आपका स्वागत है।
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-10">
            
            <!-- Map Container -->
            <div class="h-[400px] md:h-[500px] w-full bg-slate-100 relative">
                <iframe 
                    src="{{ $settings['location_map_iframe'] ?? 'https://www.google.com/maps?q=Seva+Sadan+Chabali+Ghati+Bikaner&hl=en&z=17&output=embed' }}" 
                    class="absolute inset-0 w-full h-full border-0"
                    allowfullscreen 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <div class="p-8 md:p-10 lg:p-12">
                <!-- Address Details Box -->
                <div class="bg-slate-900 rounded-2xl p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row items-center gap-8 shadow-xl">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEuNSIgZmlsbD0iI2ZmZmZmZiIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-50"></div>
                    
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl shadow-lg shrink-0 relative z-10">
                        <i class="bi bi-pin-map-fill"></i>
                    </div>
                    
                    <div class="relative z-10 text-center md:text-left flex-grow">
                        <h4 class="text-slate-400 font-bold uppercase tracking-wider text-sm mb-2 font-['Inter']">Venue Location</h4>
                        <h3 class="text-2xl font-bold text-white font-['Noto_Sans_Devanagari'] mb-2">
                            {{ $settings['location_title'] ?? 'Seva Sadan Chabali Ghati, Bikaner' }}
                        </h3>
                        <p class="text-slate-300 font-medium font-['Noto_Sans_Devanagari'] leading-relaxed text-lg">
                            {!! nl2br(e($settings['location_address'] ?? "Bikaner, Rajasthan, India\n📍 Seva Sadan 🇮🇳")) !!}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ $settings['location_share_link'] ?? 'https://share.google/C9RfibMFPueQ1JBes' }}" target="_blank" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-8 rounded-xl shadow hover:shadow-lg transition-all text-lg">
                        <i class="bi bi-navigation-fill text-xl group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                        <span>Get Directions</span>
                    </a>
                    
                    <a href="{{ $settings['location_share_link'] ?? 'https://share.google/C9RfibMFPueQ1JBes' }}" target="_blank" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold py-4 px-8 rounded-xl shadow hover:shadow-lg transition-all text-lg">
                        <i class="bi bi-box-arrow-up-right text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Open in Google Maps</span>
                    </a>
                </div>
            </div>
            
        </div>

    </div>

    <!-- Footer -->
    <div class="mt-12">
        @include('includes.footer')
    </div>

</div>
@endsection
