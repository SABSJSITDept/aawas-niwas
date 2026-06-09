@extends('layouts.app')

@section('content')

@include('includes.header')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="min-h-screen bg-slate-50 font-['Inter'] pt-24 pb-16">

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
        <span class="inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
            <i class="bi bi-journal-bookmark-fill"></i> बुकिंग पोर्टल
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 font-['Noto_Sans_Devanagari']">
            ऑनलाइन आवास बुकिंग
        </h1>
        <p class="text-slate-500 text-base max-w-2xl mx-auto font-['Noto_Sans_Devanagari']">
            अपने परिवार या संघ के साथ आने के लिए कृपया नीचे दिए गए विकल्पों में से अपनी आवश्यकता अनुसार बुकिंग का चयन करें।
        </p>
    </div>

    <!-- Booking Options -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Family/Personal Booking Card -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden text-center flex flex-col h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="relative z-10 flex-grow">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        👨‍👩‍👧‍👦
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3 font-['Noto_Sans_Devanagari']">
                        परिवार / व्यक्तिगत बुकिंग
                    </h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-['Noto_Sans_Devanagari']">
                        अगर आप अपने परिवार के साथ या व्यक्तिगत रूप से बुकिंग करना चाहते हैं, तो इस विकल्प का चयन करें। (10 से कम व्यक्तियों के लिए)
                    </p>
                </div>
                
                <div class="relative z-10 mt-auto">
                    <a href="{{ route('family-booking.create') }}" class="inline-flex items-center justify-center gap-2 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-indigo-500/30 font-['Inter']">
                        <span>Apply Now</span> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Group Booking Card -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden text-center flex flex-col h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="relative z-10 flex-grow">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        👥
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3 font-['Noto_Sans_Devanagari']">
                        समूह / संघ बुकिंग
                    </h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-['Noto_Sans_Devanagari']">
                        अगर आप किसी बड़े समूह या संघ के रूप में एक साथ बुकिंग करना चाहते हैं, तो इस विकल्प का चयन करें। (10 या उससे अधिक व्यक्तियों के लिए)
                    </p>
                </div>
                
                <div class="relative z-10 mt-auto">
                    <a href="{{ route('group.booking') }}" class="inline-flex items-center justify-center gap-2 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-500/30 font-['Inter']">
                        <span>Apply Now</span> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- General Instructions -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-slate-200">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-100 text-amber-600 rounded-full mb-4">
                    <i class="bi bi-info-circle-fill text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']">🙏 विनम्र निवेदन</h3>
                <p class="text-slate-500 mt-2 font-['Noto_Sans_Devanagari']">बुकिंग करने से पूर्व कृपया निम्नलिखित नियमों का पालन करें</p>
            </div>
            
            <hr class="border-slate-100 mb-8">
            
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">1</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">सभी आगन्तुक अपना पहचान पत्र साथ लेकर पधारें एवं कार्यालय में दें। (आधार कार्ड, ड्राइविंग लाइसेंस, वोटर कार्ड आदि)</p>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">2</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">आने की सूचना कम से कम 3 दिन पूर्व देने की कृपा करें।</p>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">3</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">आपकी बुकिंग अधिकतम 3 दिवस के लिए की जाएगी।</p>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">4</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">सभी आगन्तुक विराजित संत म.सा / सतियाजी म.सा के दर्शन का लक्ष्य रखें।</p>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">5</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">कीमती सामान का स्वयं ध्यान रखें।</p>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 mt-0.5">6</div>
                    <p class="text-slate-700 font-medium leading-relaxed font-['Noto_Sans_Devanagari']">प्रार्थना, प्रवचन, प्रतिक्रमण, संवर आदि सभी धार्मिक क्रियाओं में भाग लेने का लक्ष्य रखें।</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap Modal for Image (Preserved logic if needed in future) -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-3xl overflow-hidden shadow-2xl">
            <div class="modal-body p-0 relative">
                <button type="button" class="absolute top-4 right-4 w-8 h-8 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/70 transition-colors z-10" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img src="{{ asset('images/globalcard.jpeg') }}" alt="Know Your MID" class="w-full h-auto">
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

@endsection
