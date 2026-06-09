@extends('admin.layout')

@section('content')
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    /* Hide scrollbar for clean UI */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen">
    <!-- Header / Hero -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h4 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <span class="text-3xl">🏨</span> होटल के कमरे की सुविधाएं
            </h4>
            <p class="text-slate-500 mt-1">होटल चुनें और कमरे की सुविधाओं को सुंदर, फ़िल्टर करने योग्य कार्ड्स में देखें।</p>
        </div>
        <div class="flex flex-wrap gap-3 items-center">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input id="featureSearch" type="search" class="pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-64 transition-all" placeholder="कमरा संख्या या सुविधा खोजें...">
            </div>
            
            <select id="hotelSelect" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block py-2 px-3 transition-all">
                <option value="">-- होटल चुनें --</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->hotel_name }}</option>
                @endforeach
            </select>
            
            <button id="loadFeaturesBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm shadow-indigo-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                लोड करें
            </button>
            <a href="#" id="downloadLink" class="hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm shadow-emerald-200 items-center gap-2 flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                एक्सेल
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Side: Features List -->
        <div class="flex-1">
            <div id="featureControls" class="flex items-center gap-2 mb-4">
                <button id="viewGrid" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-indigo-50 text-indigo-700 border border-indigo-200">Grid</button>
                <button id="viewList" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">List</button>
                <div class="ml-auto text-slate-500 text-sm font-medium" id="featuresCount">0 features</div>
            </div>

            <div id="featureDetails" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Feature cards will render here -->
            </div>
        </div>

        <!-- Right Side: Details Panel -->
        <div class="w-full lg:w-80 shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sticky top-6">
                <h5 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    चुने हुए कमरे का विवरण
                </h5>
                
                <div id="sideEmpty" class="text-center py-10">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                    </div>
                    <div class="text-slate-500 text-sm">कोई कमरा चुना नहीं गया। किसी कार्ड पर क्लिक करें।</div>
                </div>

                <div id="sideContent" class="hidden space-y-4">
                    <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                        <h6 id="sideRoomNumber" class="text-xl font-bold text-indigo-900">Room --</h6>
                        <span id="sideOther" class="text-xs font-medium text-indigo-600 uppercase tracking-wide">--</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-slate-500 text-sm">Air Conditioner (AC)</span>
                            <span id="sideAc" class="font-medium text-slate-800">--</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-slate-500 text-sm">Attached Bathroom</span>
                            <span id="sideAttach" class="font-medium text-slate-800">--</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-slate-500 text-sm">Toilet Type</span>
                            <span id="sideToilet" class="font-medium text-slate-800 capitalize">--</span>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <a id="sideCall" href="#" class="hidden w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl text-sm font-medium transition-colors items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Call Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loadBtn = document.getElementById('loadFeaturesBtn');
    const hotelSelect = document.getElementById('hotelSelect');
    const container = document.getElementById('featureDetails');
    const downloadLink = document.getElementById('downloadLink');
    const searchInput = document.getElementById('featureSearch');
    const viewGrid = document.getElementById('viewGrid');
    const viewList = document.getElementById('viewList');
    const featuresCount = document.getElementById('featuresCount');

    let features = [];
    let currentView = 'grid';

    function renderCards() {
        container.innerHTML = '';
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;

        if (currentView === 'grid') {
            container.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';
        } else {
            container.className = 'flex flex-col gap-3';
        }

        features.forEach(f => {
            const matchesSearch = !q || (`${f.room_number}`.toLowerCase().includes(q) || (f.toilet_type || '').toLowerCase().includes(q));
            if (!matchesSearch) return;

            visible++;
            
            const isAc = (f.ac == 'AC' || f.ac == 1 || f.ac == '1');
            const isAttach = (f.attach_bath == 'Yes' || f.attach_bath == 1 || f.attach_bath == '1');
            
            const acBadge = isAc 
                ? '<span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>AC</span>' 
                : '<span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Non-AC</span>';
                
            const attachBadge = isAttach
                ? '<span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Attached</span>' 
                : '<span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Common</span>';

            const cardHtml = `
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 group flex flex-col h-full cursor-pointer" data-room-id="${f.id}" data-contact="${f.contact || ''}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <div>
                                <h6 class="text-lg font-bold text-slate-800 leading-tight">Room ${f.room_number}</h6>
                                <div class="text-xs font-medium text-slate-500 mt-0.5">${f.room_type || 'Standard Room'}</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                            ${f.status || 'Available'}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-5 mt-auto">
                        ${acBadge}
                        ${attachBadge}
                        <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 capitalize">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            ${f.toilet_type || '—'}
                        </span>
                    </div>

                    <button class="btn-select w-full py-2 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 rounded-lg text-sm font-semibold transition-colors border border-slate-200 hover:border-indigo-200">
                        View Details
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', cardHtml);
        });

        featuresCount.innerHTML = `<span class="font-bold text-slate-800">${visible}</span> rooms found`;
        if (visible === 0) {
            container.className = 'col-span-full';
            container.innerHTML = `
                <div class="text-center py-12 bg-white rounded-2xl border border-slate-100 border-dashed">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-slate-500 font-medium">कोई फीचर नहीं मिला।</div>
                </div>`;
        }
    }

    function setDownloadLink(hotelId) {
        downloadLink.href = `/admin/export-room-features/${hotelId}`;
        downloadLink.classList.remove('hidden');
    }

    loadBtn.addEventListener('click', function () {
        const hotelId = hotelSelect.value;
        container.innerHTML = '';
        downloadLink.classList.add('hidden');
        document.getElementById('sideContent').classList.add('hidden');
        document.getElementById('sideEmpty').classList.remove('hidden');

        if (!hotelId) {
            container.className = 'col-span-full';
            container.innerHTML = `
                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    कृपया पहले कोई होटल चुनें।
                </div>`;
            features = [];
            renderCards();
            return;
        }

        setDownloadLink(hotelId);
        
        container.className = 'col-span-full';
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-200 border-t-indigo-600 mb-2"></div>
                <div class="text-slate-500 font-medium">Loading features...</div>
            </div>`;

        fetch(`/admin/room-features/${hotelId}`)
            .then(res => res.json())
            .then(data => {
                features = data.map(d => ({
                    id: d.id || d.room_id || '',
                    room_number: d.room_number || d.room_no || '—',
                    ac: d.ac || 0,
                    attach_bath: d.attach_bath || 0,
                    toilet_type: d.toilet_type || d.toilet || '',
                    room_type: d.room_type || '',
                    status: d.status || 'Available',
                    contact: d.contact_number || ''
                }));
                renderCards();
            })
            .catch(err => {
                console.error(err);
                container.className = 'col-span-full';
                container.innerHTML = `
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        डेटा लोड करने में त्रुटि हुई।
                    </div>`;
            });
    });

    searchInput.addEventListener('input', renderCards);
    
    viewGrid.addEventListener('click', function () { 
        currentView = 'grid'; 
        viewGrid.className = 'px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-indigo-50 text-indigo-700 border border-indigo-200';
        viewList.className = 'px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-white text-slate-600 border border-slate-200 hover:bg-slate-50';
        renderCards(); 
    });
    
    viewList.addEventListener('click', function () { 
        currentView = 'list'; 
        viewList.className = 'px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-indigo-50 text-indigo-700 border border-indigo-200';
        viewGrid.className = 'px-3 py-1.5 rounded-md text-sm font-medium transition-colors bg-white text-slate-600 border border-slate-200 hover:bg-slate-50';
        renderCards(); 
    });

    container.addEventListener('click', function (e) {
        const sel = e.target.closest('.btn-select') || e.target.closest('.bg-white');
        if (!sel || !sel.closest('.bg-white').dataset.roomId) return;
        
        document.querySelectorAll('.btn-select').forEach(b => {
            b.className = 'btn-select w-full py-2 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 rounded-lg text-sm font-semibold transition-colors border border-slate-200 hover:border-indigo-200';
        });
        
        const card = sel.closest('.bg-white');
        const btn = card.querySelector('.btn-select');
        if (btn) {
            btn.className = 'btn-select w-full py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold transition-colors border border-indigo-600 shadow-sm shadow-indigo-200';
        }
        
        const roomId = card.dataset.roomId;
        const feature = features.find(f => String(f.id) === String(roomId));
        if (!feature) return;

        document.getElementById('sideEmpty').classList.add('hidden');
        document.getElementById('sideContent').classList.remove('hidden');
        
        document.getElementById('sideRoomNumber').textContent = `Room ${feature.room_number}`;
        
        const isAc = (feature.ac == 'AC' || feature.ac == 1 || feature.ac == '1');
        const isAttach = (feature.attach_bath == 'Yes' || feature.attach_bath == 1 || feature.attach_bath == '1');
        
        document.getElementById('sideAc').innerHTML = isAc 
            ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">हाँ</span>' 
            : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">नहीं</span>';
            
        document.getElementById('sideAttach').innerHTML = isAttach 
            ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">हाँ</span>' 
            : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">नहीं</span>';
            
        document.getElementById('sideToilet').textContent = feature.toilet_type || '—';
        document.getElementById('sideOther').textContent = feature.room_type || 'Standard Room';
        
        const callLink = document.getElementById('sideCall');
        if (feature.contact) {
            callLink.href = `tel:${feature.contact}`;
            callLink.classList.remove('hidden');
            callLink.classList.add('flex');
        } else {
            callLink.classList.add('hidden');
            callLink.classList.remove('flex');
        }
    });
});
</script>
@endsection
