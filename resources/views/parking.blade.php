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
        <div class="absolute w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[100px] -top-32 -left-32 pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] bg-sky-500/15 rounded-full blur-[100px] -bottom-32 -right-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-1.5 bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-medium px-4 py-1.5 rounded-full mb-5 font-['Noto_Sans_Devanagari']">
                <i class="bi bi-geo-alt-fill"></i> Parking Locations
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 font-['Noto_Sans_Devanagari']">
                पार्किंग स्थान
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-['Noto_Sans_Devanagari']">
                आवास निवास और आयोजन स्थल के पास सुरक्षित एवं सुविधाजनक पार्किंग व्यवस्था।
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Parking Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @forelse($parkingLocations as $location)
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col">
                
                <div class="p-6 pb-0 relative">
                    <!-- Icon Bubble -->
                    <div class="absolute right-6 top-6 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl text-white shadow-md transform group-hover:scale-110 group-hover:rotate-3 transition-transform" style="background: linear-gradient(135deg, {{ $location->color }} 0%, {{ $location->color_light }} 100%)">
                        <i class="bi {{ $location->icon }}"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-slate-800 font-['Noto_Sans_Devanagari'] pr-16 mb-2">{{ $location->name }}</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-wide uppercase">Parking Zone</p>
                </div>
                
                <div class="p-6 pt-4 flex-grow flex flex-col">
                    <div class="mt-auto grid grid-cols-1 gap-2">
                        <a href="{{ $location->map_url }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-3 px-4 rounded-xl shadow-sm hover:shadow transition-colors font-['Noto_Sans_Devanagari']">
                            <i class="bi bi-map-fill"></i> नक़्शे में देखें
                        </a>
                    </div>
                </div>
                
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="bi bi-p-circle-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 font-['Noto_Sans_Devanagari']">कोई पार्किंग स्थान नहीं मिला</h3>
                <p class="text-slate-500 font-['Noto_Sans_Devanagari'] mt-1">वर्तमान में कोई पार्किंग स्थान जोड़ा नहीं गया है।</p>
            </div>
            @endforelse
        </div>

        <!-- INFO SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 lg:p-10 mb-8">
            <h3 class="text-xl font-bold text-slate-800 font-['Noto_Sans_Devanagari'] mb-8 flex items-center gap-2">
                <i class="bi bi-info-circle-fill text-indigo-500"></i> महत्वपूर्ण जानकारी
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-800 font-['Noto_Sans_Devanagari'] mb-1">24/7 उपलब्ध</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-['Noto_Sans_Devanagari']">सभी पार्किंग स्थान दर्शनार्थियों के लिए दिन रात खुले हैं।</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-800 font-['Noto_Sans_Devanagari'] mb-1">सुरक्षित</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-['Noto_Sans_Devanagari']">सभी स्थान पूरी तरह से सुरक्षित हैं एवं वालंटियर्स की निगरानी में हैं।</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-cursor-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-800 font-['Noto_Sans_Devanagari'] mb-1">सुगम पहुँच</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-['Noto_Sans_Devanagari']">पार्किंग स्थल मुख्य मार्ग और आयोजन स्थल के बिलकुल नज़दीक हैं।</p>
                    </div>
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
