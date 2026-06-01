@extends('layouts.app')

@section('content')
@include('includes.header')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="min-h-screen bg-slate-50 font-['Inter']">

    <!-- HERO SECTION -->
    <div class="relative bg-slate-900 overflow-hidden py-10 lg:py-12">
        <!-- Glow Effects -->
        <div class="absolute w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[100px] -top-32 -left-32 pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] bg-red-500/15 rounded-full blur-[100px] -bottom-32 -right-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12">
            <!-- Left Content -->
            <div class="flex-1 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-medium px-3 py-1 rounded-full mb-4">
                    <i class="bi bi-telephone-fill"></i> हेल्पलाइन सेवा
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-3 font-['Noto_Sans_Devanagari']">
                    हेल्पलाइन नंबर
                </h1>
                <p class="text-slate-400 text-base md:text-lg leading-relaxed mb-6 font-['Noto_Sans_Devanagari']">
                    आपकी समस्या का समाधान हमारी प्राथमिकता है।<br class="hidden sm:block">किसी भी माध्यम से हमसे जुड़ें।
                </p>
                
                <!-- Stats -->
                <div class="flex items-center justify-center lg:justify-start gap-5 lg:gap-8">
                    <div class="flex flex-col">
                        <span class="text-xl md:text-2xl font-extrabold text-white leading-none">24/7</span>
                        <span class="text-[11px] text-slate-400 mt-1 font-['Noto_Sans_Devanagari']">सेवा उपलब्ध</span>
                    </div>
                    <div class="w-px h-8 bg-slate-700"></div>
                    <div class="flex flex-col">
                        <span class="text-xl md:text-2xl font-extrabold text-white leading-none">7+</span>
                        <span class="text-[11px] text-slate-400 mt-1 font-['Noto_Sans_Devanagari']">संपर्क माध्यम</span>
                    </div>
                    <div class="w-px h-8 bg-slate-700"></div>
                    <div class="flex flex-col">
                        <span class="text-xl md:text-2xl font-extrabold text-white leading-none">तुरंत</span>
                        <span class="text-[11px] text-slate-400 mt-1 font-['Noto_Sans_Devanagari']">जवाब</span>
                    </div>
                </div>
            </div>

            <!-- Right Card -->
            <div class="shrink-0 w-full lg:w-[280px]">
                <div class="bg-white rounded-2xl p-5 text-center shadow-2xl shadow-black/30 border border-slate-100 relative overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-50/30"></div>
                    <div class="relative z-10">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl text-indigo-600">
                            <i class="bi bi-headset"></i>
                        </div>
                        <p class="text-indigo-600/80 text-[10px] font-bold mb-2 font-['Noto_Sans_Devanagari'] uppercase tracking-wider">सहायता</p>
                        <a href="tel:+919876543210" class="block text-base font-extrabold text-slate-800 hover:text-indigo-600 transition-colors mb-3">
                            +91 98765 43210 <span class="block text-[12px] font-semibold text-slate-500 mt-0.5 font-['Noto_Sans_Devanagari']">प्रतिनिधि 1</span>
                        </a>
                        <a href="tel:+919876543211" class="block text-base font-extrabold text-slate-800 hover:text-indigo-600 transition-colors mb-4">
                            +91 98765 43211 <span class="block text-[12px] font-semibold text-slate-500 mt-0.5 font-['Noto_Sans_Devanagari']">प्रतिनिधि 2</span>
                        </a>
                        <span class="inline-flex items-center gap-1.5 bg-green-50 border border-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full font-['Noto_Sans_Devanagari'] shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> हमेशा सक्रिय
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Label -->
        <div class="text-center mb-12">
            <span class="inline-block bg-white border border-slate-200 rounded-full px-6 py-2 text-sm font-semibold text-slate-600 shadow-sm font-['Noto_Sans_Devanagari']">
                सभी संपर्क माध्यम
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

            <!-- 1. सामान्य सहायता -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-indigo-50/80 p-4 flex items-center gap-3 border-b border-indigo-100">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-lg text-indigo-600 shrink-0">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-indigo-600/70 uppercase tracking-wider">सहायता</div>
                        <h3 class="text-base font-bold text-indigo-900 mt-0.5 font-['Noto_Sans_Devanagari']">सामान्य सहायता</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">बुकिंग और सामान्य प्रश्नों के लिए समर्पित टीम से संपर्क करें।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 3</span>
                            <a href="tel:+919876543212" class="inline-flex items-center justify-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43212
                            </a>
                        </div>
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 4</span>
                            <a href="tel:+919876543213" class="inline-flex items-center justify-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43213  
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. आपातकालीन -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-red-50/80 p-4 flex items-center gap-3 border-b border-red-100 relative overflow-hidden">
                    <div class="absolute inset-0 bg-red-100/30 animate-pulse mix-blend-overlay"></div>
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-lg text-red-600 shrink-0 relative z-10">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="text-[10px] font-semibold text-red-600/70 uppercase tracking-wider">आपातकाल</div>
                        <h3 class="text-base font-bold text-red-900 mt-0.5 font-['Noto_Sans_Devanagari']">आपातकालीन सहायता</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">तत्काल चिकित्सा या आपातकालीन स्थिति में 24/7 उपलब्ध प्रतिनिधि।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 5</span>
                            <a href="tel:+919876543214" class="inline-flex items-center justify-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43214
                            </a>
                        </div>
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 6</span>
                            <a href="tel:+919876543215" class="inline-flex items-center justify-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43215
                            </a>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-t border-red-50 flex items-center gap-2 text-[11px] text-slate-500 font-['Noto_Sans_Devanagari']">
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full font-['Inter']">
                            <span class="w-1 h-1 rounded-full bg-green-600 animate-pulse"></span> LIVE
                        </span>
                        <span class="text-green-700 font-medium">आपातकालीन सेवा 24/7</span>
                    </div>
                </div>
            </div>

            <!-- 3. चिकित्सा सेवा -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-rose-50/80 p-4 flex items-center gap-3 border-b border-rose-100">
                    <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center text-lg text-rose-600 shrink-0">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-rose-600/70 uppercase tracking-wider">चिकित्सा</div>
                        <h3 class="text-base font-bold text-rose-900 mt-0.5 font-['Noto_Sans_Devanagari']">चिकित्सा सेवा</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">स्वास्थ्य संबंधी सेवाएं, चिकित्सा परामर्श और आपातकालीन चिकित्सा सहायता।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 7</span>
                            <a href="tel:+919876543216" class="inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43216
                            </a>
                        </div>
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 8</span>
                            <a href="tel:+919876543217" class="inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43217
                            </a>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] text-slate-500 font-['Noto_Sans_Devanagari']">
                        <i class="bi bi-circle-fill text-rose-500 text-[6px]"></i> 24/7 चिकित्सा सहायता
                    </div>
                </div>
            </div>

            <!-- 4. व्हाट्सएप -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-emerald-50/80 p-4 flex items-center gap-3 border-b border-emerald-100">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-lg text-emerald-600 shrink-0">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-emerald-600/70 uppercase tracking-wider">व्हाट्सएप</div>
                        <h3 class="text-base font-bold text-emerald-900 mt-0.5 font-['Noto_Sans_Devanagari']">व्हाट्सएप संपर्क</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">त्वरित चैट, फ़ोटो या दस्तावेज़ साझा करने की सुविधा के साथ 24/7 संपर्क करें।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 9</span>
                            <a href="https://wa.me/919876543218" target="_blank" class="inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-whatsapp"></i> 98765 43218
                            </a>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] text-slate-500 font-['Noto_Sans_Devanagari']">
                        <i class="bi bi-circle-fill text-emerald-500 text-[6px]"></i> 24/7 व्हाट्सएप उपलब्ध
                    </div>
                </div>
            </div>

            <!-- 5. कार्यालय -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-amber-50/80 p-4 flex items-center gap-3 border-b border-amber-100">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-lg text-amber-600 shrink-0">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-amber-600/70 uppercase tracking-wider">कार्यालय</div>
                        <h3 class="text-base font-bold text-amber-900 mt-0.5 font-['Noto_Sans_Devanagari']">मुख्य कार्यालय संपर्क</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">किसी भी प्रकार की प्रशासनिक सहायता के लिए कार्यालय से संपर्क करें।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 10</span>
                            <a href="tel:+919876543219" class="inline-flex items-center justify-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43219
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-3 border-t border-slate-100">
                        <div class="flex items-start gap-1.5 text-xs text-slate-600 mb-1.5 font-['Noto_Sans_Devanagari']">
                            <i class="bi bi-geo-alt-fill text-amber-600 mt-0.5 text-[10px]"></i>
                            <span>गोगागेट, बीकानेर, राजस्थान</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-600 hover:text-amber-700 transition-colors">
                            <i class="bi bi-map-fill"></i> Google Maps पर देखें
                            <i class="bi bi-arrow-up-right-circle text-[10px] opacity-70"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6. भोजनशाला -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-orange-50/80 p-4 flex items-center gap-3 border-b border-orange-100">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-lg text-orange-600 shrink-0">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-orange-600/70 uppercase tracking-wider">भोजनशाला</div>
                        <h3 class="text-base font-bold text-orange-900 mt-0.5 font-['Noto_Sans_Devanagari']">भोजनशाला संपर्क</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">भोजन व्यवस्था, भोजनशाला समय-सारिणी एवं विशेष आहार संबंधी प्रश्नों के लिए संपर्क करें।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 11</span>
                            <a href="tel:+919876543220" class="inline-flex items-center justify-center gap-1.5 bg-orange-50 hover:bg-orange-100 text-orange-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43220
                            </a>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] text-slate-500 font-['Noto_Sans_Devanagari']">
                        <i class="bi bi-clock text-slate-400"></i> भोजन समय अनुसार उपलब्ध
                    </div>
                </div>
            </div>

            <!-- 7. परिवहन -->
            <div class="hl-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col border border-slate-200 opacity-0 translate-y-6">
                <div class="bg-sky-50/80 p-4 flex items-center gap-3 border-b border-sky-100">
                    <div class="w-10 h-10 rounded-lg bg-sky-100 flex items-center justify-center text-lg text-sky-600 shrink-0">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-sky-600/70 uppercase tracking-wider">परिवहन</div>
                        <h3 class="text-base font-bold text-sky-900 mt-0.5 font-['Noto_Sans_Devanagari']">परिवहन एवं यातायात</h3>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">यात्रा व्यवस्था, बस-शटल टाइमिंग एवं स्थानांतरण सहायता के लिए संपर्क करें।</p>
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2">
                            <span class="text-[14px] font-semibold text-slate-800 font-['Noto_Sans_Devanagari']">प्रतिनिधि 12</span>
                            <a href="tel:+919876543221" class="inline-flex items-center justify-center gap-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 text-[13px] font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <i class="bi bi-telephone-fill"></i> 98765 43221
                            </a>
                        </div>
                    </div>
                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] text-slate-500 font-['Noto_Sans_Devanagari']">
                        <i class="bi bi-clock text-slate-400"></i> यात्रा समय अनुसार उपलब्ध
                    </div>
                </div>
            </div>

        </div><!-- /grid -->
    </div><!-- /container -->
</div><!-- /min-h-screen -->

<!-- JavaScript for scroll animation -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.hl-card');
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add a small delay based on index for a staggered effect
                setTimeout(() => {
                    entry.target.classList.remove('opacity-0', 'translate-y-6');
                }, index * 100);
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(c => io.observe(c));
});
</script>

@include('includes.footer')
@endsection
