@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-['Inter'] pt-4 pb-20">

    @include('includes.header')

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Tailwind CDN for robust application -->
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">

        <!-- 1. Hero Banner -->
        <div class="group relative rounded-none sm:rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 mb-8 cursor-pointer bg-white -mx-4 sm:mx-0" data-bs-toggle="modal" data-bs-target="#imageModal">
            <img src="{{ asset('images/1.jpeg') }}" alt="Main Event Poster" class="w-full h-auto max-h-[500px] object-contain transition-transform duration-500 group-hover:scale-[1.02]" data-img="{{ asset('images/1.jpeg') }}" loading="lazy">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300 pointer-events-none"></div>
        </div>

        <!-- 2. Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Registration -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 flex flex-col h-full group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-16 -mt-16 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex-grow">
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3 font-['Noto_Sans_Devanagari']">दर्शनार्थी पंजीकरण</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 font-['Noto_Sans_Devanagari']">समर्पण महोत्सव - 2026 के अवसर पर दर्शन हेतु पंजीकरण करें। सुरक्षित और सरल प्रक्रिया।</p>
                </div>
                <div class="relative z-10 mt-auto">
                    <a href="{{ route('other_form') }}" class="inline-flex items-center justify-center gap-2 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-sm font-['Inter']">
                        <span>पंजीकरण करें</span> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Accommodation -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 flex flex-col h-full group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-bl-full -mr-16 -mt-16 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex-grow">
                    <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3 font-['Noto_Sans_Devanagari']">आवास - निवास</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4 font-['Noto_Sans_Devanagari']">आपकी आवास-निवास जानकारी और कमरा विवरण देखें। बुकिंग की स्थिति जानें।</p>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border-l-4 border-rose-500 mb-6">
                        <i class="bi bi-info-circle-fill text-rose-500"></i>
                        <span class="text-xs font-medium text-slate-600 font-['Noto_Sans_Devanagari']">आपका कमरा विवरण तुरंत देखें</span>
                    </div>
                </div>
                <div class="relative z-10 mt-auto flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.booking.form') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-sm font-['Inter']">
                        <i class="bi bi-door-open"></i> <span>मेरा कमरा</span>
                    </a>
                    <a href="{{ route('user.booking.form') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold py-3 px-4 rounded-xl transition-all shadow-sm font-['Inter']">
                        <i class="bi bi-eye"></i> <span>विवरण</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. Info Banner 1 -->
        <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 mb-12 cursor-pointer bg-white" data-bs-toggle="modal" data-bs-target="#imageModal">
            <img src="{{ asset('images/2.jpeg') }}" alt="Information" class="w-full h-auto object-contain transition-transform duration-500 group-hover:scale-[1.02]" data-img="{{ asset('images/2.jpeg') }}">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300 pointer-events-none"></div>
        </div>

        <!-- 4. News & Announcements -->
        <div id="news-section" class="mb-12 scroll-mt-24">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']">समाचार / सूचना</h2>
                    <p class="text-sm text-slate-500 mt-1 font-['Noto_Sans_Devanagari']">अत्यावश्यक सूचनाएँ और ताज़ा समाचार</p>
                </div>
                <div class="hidden sm:flex items-center justify-center w-12 h-12 bg-amber-50 text-amber-500 rounded-full">
                    <i class="bi bi-newspaper text-xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5">
                @if($news->count() > 0)
                    @foreach($news as $newsItem)
                        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow border border-slate-200 flex flex-col md:flex-row gap-5 items-start group">
                            @if($newsItem->image)
                                <div class="w-full md:w-56 shrink-0 rounded-xl overflow-hidden cursor-pointer bg-slate-100" data-img="{{ asset($newsItem->image) }}" data-bs-toggle="modal" data-bs-target="#imageModal">
                                    <img src="{{ asset($newsItem->image) }}" alt="{{ $newsItem->title }}" class="w-full h-40 md:h-32 object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                                </div>
                            @endif
                            <div class="flex-grow flex flex-col h-full">
                                <h4 class="text-lg font-bold text-slate-800 mb-2 font-['Noto_Sans_Devanagari'] group-hover:text-indigo-600 transition-colors">{{ $newsItem->title }}</h4>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-3 bg-slate-50 self-start px-2.5 py-1 rounded-md">
                                    <i class="bi bi-calendar-event"></i> {{ $newsItem->created_at->format('d M Y') }}
                                </div>
                                <p class="text-sm text-slate-600 line-clamp-2 mb-4 leading-relaxed font-['Noto_Sans_Devanagari']">
                                    {{ $newsItem->content }}
                                </p>
                                <div class="flex flex-wrap items-center gap-3 mt-auto pt-2">
                                    <button class="view-full inline-flex items-center gap-1.5 text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors" data-title="{{ htmlentities($newsItem->title) }}" data-content="{{ htmlentities($newsItem->content) }}">
                                        पूर्ण पढ़ें <i class="bi bi-chevron-right text-[10px]"></i>
                                    </button>
                                    @if($newsItem->external_link)
                                        <a href="{{ $newsItem->external_link }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="bi bi-link-45deg"></i> विवरण
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-10 text-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <p class="text-slate-500 font-medium font-['Noto_Sans_Devanagari']">अभी कोई समाचार उपलब्ध नहीं है।</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 5. Categories / Services Grid -->
        <div class="mb-12">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']">सुविधाएँ एवं सेवाएँ</h2>
                    <p class="text-sm text-slate-500 mt-1 font-['Noto_Sans_Devanagari']">विभिन्न सेवाओं के लिए त्वरित लिंक</p>
                </div>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-8 gap-4 sm:gap-6 justify-items-center">
                <!-- Locations -->
                <a href="{{ route('location.show') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-indigo-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-lg group-hover:border-indigo-600 transition-all duration-300">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors text-center">Locations</span>
                </a>
                
                <!-- Parking -->
                <a href="{{ route('parking') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-blue-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-lg group-hover:border-blue-600 transition-all duration-300">
                        <i class="bi bi-p-square-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-blue-600 transition-colors text-center">Parking</span>
                </a>

                <!-- Sadhu Sadhvi -->
                <a href="{{ route('sadhu-sadvi.public') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-orange-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-lg group-hover:border-orange-600 transition-all duration-300">
                        <i class="bi bi-person-heart"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-orange-600 transition-colors text-center leading-tight">Sadhu Sadhvi</span>
                </a>

                <!-- Helpline -->
                <a href="{{ route('helpline-numbers') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-teal-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-teal-600 group-hover:text-white group-hover:shadow-lg group-hover:border-teal-600 transition-all duration-300">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-teal-600 transition-colors text-center">Helpline</span>
                </a>

                <!-- Medical -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#medicalEmergencyModal" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-red-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-red-600 group-hover:text-white group-hover:shadow-lg group-hover:border-red-600 transition-all duration-300">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-red-600 transition-colors text-center">Medical</span>
                </a>

                <!-- Feedback -->
                <a href="{{ route('feedback.form') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-purple-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-lg group-hover:border-purple-600 transition-all duration-300">
                        <i class="bi bi-chat-square-text-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-purple-600 transition-colors text-center">Feedback</span>
                </a>

                <!-- Events -->
                <a href="#news-section" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-amber-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-amber-600 group-hover:text-white group-hover:shadow-lg group-hover:border-amber-600 transition-all duration-300">
                        <i class="bi bi-calendar4-event"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-amber-600 transition-colors text-center">Events</span>
                </a>

                <!-- BhojanShala -->
                <a href="{{ route('bhojanshala') }}" class="group flex flex-col items-center transition-all duration-300 hover:-translate-y-1 w-full">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-100 text-rose-600 flex items-center justify-center text-2xl mb-3 group-hover:bg-rose-600 group-hover:text-white group-hover:shadow-lg group-hover:border-rose-600 transition-all duration-300">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-rose-600 transition-colors text-center">BhojanShala</span>
                </a>
            </div>
        </div>

        <!-- Additional Banners -->
        <div class="group relative rounded-none sm:rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 mb-6 cursor-pointer -mx-4 sm:mx-0 bg-white" data-bs-toggle="modal" data-bs-target="#imageModal">
            <img src="{{ asset('images/3.jpeg') }}" alt="Guidelines" class="w-full h-auto object-contain transition-transform duration-500 group-hover:scale-[1.02]" data-img="{{ asset('images/3.jpeg') }}">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300 pointer-events-none"></div>
        </div>

        <div class="group relative rounded-none sm:rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer -mx-4 sm:mx-0 bg-white" data-bs-toggle="modal" data-bs-target="#imageModal">
            <img src="{{ asset('images/4.jpeg') }}" alt="Guidelines" class="w-full h-auto object-contain transition-transform duration-500 group-hover:scale-[1.02]" data-img="{{ asset('images/4.jpeg') }}">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300 pointer-events-none"></div>
        </div>

    </div>

</div>

<div class="bg-white border-t border-slate-200 pt-8 pb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('includes.footer')
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent shadow-none border-0 relative">
            <button type="button" class="absolute -top-10 right-0 w-8 h-8 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-sm transition-colors z-50 focus:outline-none" data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" class="w-full h-auto max-h-[85vh] object-contain rounded-2xl shadow-2xl" alt="Preview">
            </div>
        </div>
    </div>
</div>

<!-- News content modal -->
<div class="modal fade" id="newsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 bg-white">
                <h5 class="text-xl font-bold text-slate-800 font-['Noto_Sans_Devanagari']" id="newsModalTitle">शीर्‍षक</h5>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors focus:outline-none" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-5 sm:p-6 bg-slate-50 text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-wrap font-['Noto_Sans_Devanagari'] max-h-[60vh] overflow-y-auto" id="newsModalBody"></div>
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-white flex justify-end">
                <button type="button" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold py-2 px-6 rounded-xl transition-colors" data-bs-dismiss="modal">बंद करें</button>
            </div>
        </div>
    </div>
</div>

<!-- Medical Emergency Modal -->
<div class="modal fade" id="medicalEmergencyModal" tabindex="-1" aria-labelledby="medicalEmergencyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-5 sm:p-6 border-b border-red-100 bg-red-50/50">
                <h5 class="text-xl font-bold text-red-600 flex items-center gap-2 font-['Noto_Sans_Devanagari']" id="medicalEmergencyLabel">
                    <i class="bi bi-heart-pulse-fill text-2xl"></i> चिकित्सा सेवा
                </h5>
                <button type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-red-500 hover:bg-red-100 transition-colors focus:outline-none shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-5 sm:p-6 bg-white">
                <p class="text-slate-600 mb-6 font-medium text-sm font-['Noto_Sans_Devanagari']">आपातकालीन स्थिति में कृपया तुरंत नीचे दिए गए नंबरों पर संपर्क करें:</p>
                
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 border-l-4 border-l-red-500 hover:bg-red-50/30 transition-colors">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">#####</div>
                        <a href="tel:**********" class="text-red-600 hover:text-red-700 font-bold text-lg flex items-center gap-2 transition-colors">
                            <i class="bi bi-telephone-fill"></i> **********
                        </a>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 border-l-4 border-l-red-500 hover:bg-red-50/30 transition-colors">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">#######</div>
                        <a href="tel:**********" class="text-red-600 hover:text-red-700 font-bold text-lg flex items-center gap-2 transition-colors">
                            <i class="bi bi-telephone-fill"></i> **********
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                <button type="button" class="w-full bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold py-2.5 px-6 rounded-xl transition-colors" data-bs-dismiss="modal">बंद करें</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Open image in modal
    document.querySelectorAll('[data-bs-target="#imageModal"]').forEach(el => {
        el.addEventListener('click', function (e) {
            let img = this.querySelector('img') || this;
            const src = img.dataset.img || img.getAttribute('src') || '';
            const modalImage = document.getElementById('modalImage');
            if (src) {
                modalImage.src = src;
            }
        });
    });

    // View full news content (modal)
    document.querySelectorAll('.view-full').forEach(btn => {
        btn.addEventListener('click', function () {
            const title = this.getAttribute('data-title');
            const content = this.getAttribute('data-content');
            
            document.getElementById('newsModalTitle').textContent = decodeHTML(title);
            document.getElementById('newsModalBody').textContent = decodeHTML(content);
            
            new bootstrap.Modal(document.getElementById('newsModal')).show();
        });
    });

    // Helper to decode HTML entities
    function decodeHTML(html) {
        const txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }
</script>
@endsection
