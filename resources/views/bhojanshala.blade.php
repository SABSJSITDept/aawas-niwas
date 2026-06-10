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
        <div class="absolute w-[500px] h-[500px] bg-amber-500/20 rounded-full blur-[100px] -top-32 -left-32 pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] bg-orange-500/15 rounded-full blur-[100px] -bottom-32 -right-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-1.5 bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs font-medium px-4 py-1.5 rounded-full mb-5 font-['Noto_Sans_Devanagari']">
                <i class="bi bi-cup-hot-fill"></i> सात्विक भोजन व्यवस्था
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 font-['Noto_Sans_Devanagari']">
                भोजनशाला
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-['Noto_Sans_Devanagari']">
                दीक्षा महोत्सव में पधारे सभी दर्शनार्थियों के लिए शुद्ध एवं सात्विक भोजन की उत्तम व्यवस्था।
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- TIMINGS CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-amber-50/50 p-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']">भोजन का समय</h2>
                        <p class="text-sm text-slate-500 font-['Noto_Sans_Devanagari']">कृपया समय का विशेष ध्यान रखें</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 group hover:bg-amber-50 hover:border-amber-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-sunrise text-amber-500 text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-semibold text-slate-700 font-['Noto_Sans_Devanagari'] text-base md:text-lg">नवकारशी (सुबह)</span>
                            </div>
                            <span class="font-bold text-amber-700 bg-amber-100/50 px-3 py-1 rounded-lg text-sm md:text-base">{{ $settings['bhojanshala_morning'] ?? '07:15 AM - 08:45 AM' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 group hover:bg-orange-50 hover:border-orange-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-sun-fill text-orange-500 text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-semibold text-slate-700 font-['Noto_Sans_Devanagari'] text-base md:text-lg">दोपहर का भोजन</span>
                            </div>
                            <span class="font-bold text-orange-700 bg-orange-100/50 px-3 py-1 rounded-lg text-sm md:text-base">{{ $settings['bhojanshala_afternoon'] ?? '11:00 AM - 02:00 PM' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 group hover:bg-rose-50 hover:border-rose-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-sunset-fill text-rose-500 text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-semibold text-slate-700 font-['Noto_Sans_Devanagari'] text-base md:text-lg">शाम का भोजन</span>
                            </div>
                            <span class="font-bold text-rose-700 bg-rose-100/50 px-3 py-1 rounded-lg text-sm md:text-base">{{ $settings['bhojanshala_evening'] ?? '05:00 PM - सूर्यास्त तक' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RULES CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-indigo-50/50 p-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']">आवश्यक सूचनाएँ</h2>
                        <p class="text-sm text-slate-500 font-['Noto_Sans_Devanagari']">भोजनशाला के कुछ महत्वपूर्ण नियम</p>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        @php
                            $rulesText = $settings['bhojanshala_rules'] ?? "भोजनशाला में कृपया अनुशासन और शांति बनाए रखें।\nभोजन झूठा न छोड़ें, उतना ही लें जितनी आवश्यकता हो।\nसूर्यास्त के पश्चात भोजनशाला पूर्णतः बंद रहेगी। कृपया समय का ध्यान रखें।";
                            $rulesArray = array_filter(array_map('trim', explode("\n", $rulesText)));
                        @endphp
                        @foreach($rulesArray as $rule)
                        <li class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <i class="bi bi-check2-circle text-indigo-500 text-xl shrink-0 mt-0.5"></i>
                            <span class="text-slate-600 font-medium leading-relaxed font-['Noto_Sans_Devanagari'] text-sm md:text-base">{{ $rule }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

        <!-- LOCATION CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                
                <!-- Left Details -->
                <div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-slate-100 bg-emerald-50/30">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 font-['Noto_Sans_Devanagari'] mb-3">भोजनशाला का स्थान</h2>
                    <p class="text-slate-600 text-lg mb-8 font-['Noto_Sans_Devanagari'] leading-relaxed">
                        {{ $settings['bhojanshala_location_text'] ?? 'आयोजन स्थल (Dummy Location)' }}
                    </p>
                    
                    <a href="{{ $settings['bhojanshala_map_link'] ?? '#' }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-sm hover:shadow-md transition-all font-['Noto_Sans_Devanagari'] w-full sm:w-auto text-lg">
                        <i class="bi bi-map"></i> Google Maps पर देखें
                    </a>
                </div>

                <!-- Right Map -->
                <div class="lg:col-span-7 h-[300px] lg:h-auto min-h-[400px] relative bg-slate-100">
                    <iframe 
                        src="{{ $settings['bhojanshala_map_iframe'] ?? 'https://maps.google.com/maps?q=Dummy+Location&t=&z=15&ie=UTF8&iwloc=&output=embed' }}" 
                        class="absolute inset-0 w-full h-full border-0"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </div>

    </div>

    <div class="mt-12">
        @includeIf('includes.footer')
    </div>
</div>
@endsection
