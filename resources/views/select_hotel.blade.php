@extends('admin.layout')

@section('content')
<div class="container my-5">

    <!-- Hero -->
    <div class="bg-light rounded-4 p-5 mb-4 hero-shadow">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div>
                <h2 class="mb-1">🏨 होटल जानकारी</h2>
                <p class="text-muted mb-0">होटल चुनें, खोजें और तुरंत पूरी जानकारी देखें — modern और तेज़ UI</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <input id="hotelSearch" type="search" class="form-control form-control-lg" placeholder="होटल का नाम खोजें..." style="min-width:300px;">
                <select id="hotelSelect" class="form-select form-select-lg">
                    <option value="all">-- सभी होटल --</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->hotel_name }}</option>
                    @endforeach
                </select>
                <button id="checkButton" class="btn btn-primary btn-lg">
                    <i class="bi bi-search me-2"></i>खोजें
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left: Hotels Grid -->
        <div class="col-lg-8 mb-4">
            <div id="hotelsGrid" class="row g-4">
                @foreach($hotels as $hotel)
                    <div class="col-md-6">
                        <div class="card hotel-card h-100" data-hotel-id="{{ $hotel->id }}">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $hotel->hotel_name }}</h5>
                                        <div class="text-muted small">प्रभारी: {{ $hotel->incharge_name ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">ID: {{ $hotel->id }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 mb-3">
                                    <div class="d-flex gap-3 small text-muted">
                                        <div><i class="bi bi-people-fill me-1"></i> कमरे: {{ $hotel->total_rooms ?? '—' }}</div>
                                        <div><i class="bi bi-droplet me-1"></i> कॉमन बाथ: {{ $hotel->common_bath ? 'हाँ' : 'नहीं' }}</div>
                                        <div><i class="bi bi-arrow-up-square me-1"></i> लिफ्ट: {{ $hotel->lift ? 'हाँ' : 'नहीं' }}</div>
                                    </div>
                                </div>

                                <div class="mt-auto d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm btn-view" data-id="{{ $hotel->id }}">View Details</button>
                                    <a href="tel:{{ $hotel->contact_number }}" class="btn btn-outline-secondary btn-sm">Call</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Details Panel -->
        <div class="col-lg-4">
            <div id="hotelDetailsPanel" class="card shadow-sm sticky-top" style="top:20px;">
                <div class="card-body">
                    <div id="detailsLoading" class="text-center py-5" style="display:none;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-3 text-muted">Loading hotel details...</div>
                    </div>

                    <div id="detailsEmpty" class="text-center py-5">
                        <i class="bi bi-info-circle display-4 text-muted"></i>
                        <p class="mt-3 mb-0 text-muted">कृपया किसी होटल पर क्लिक करें या ऊपर से चुनें।</p>
                    </div>

                    <div id="detailsContent" style="display:none;">
                        <h5 id="detailHotelName" class="mb-1"></h5>
                        <div class="text-muted small mb-3" id="detailIncharge"></div>

                        <div class="row g-2 mb-3">
                            <div class="col-6"><strong>Contact</strong><div id="detailContact" class="text-muted"></div></div>
                            <div class="col-6"><strong>Rooms</strong><div id="detailRooms" class="text-muted"></div></div>
                        </div>

                        <div class="mb-3">
                            <strong>Facilities</strong>
                            <div class="mt-2 small text-muted" id="detailFacilities"></div>
                        </div>

                        <div class="mb-3">
                            <strong>Address</strong>
                            <div class="text-muted small" id="detailAddress"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <a id="directionsLink" href="#" target="_blank" class="btn btn-success">Get Directions</a>
                            <button id="openMapBtn" class="btn btn-outline-secondary">Open in Map</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .hero-shadow { box-shadow: 0 10px 30px rgba(15,23,42,0.06); }
    .hotel-card { border-radius: 12px; transition: transform .18s ease, box-shadow .18s ease; }
    .hotel-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,23,42,0.08); }
    .sticky-top { position: sticky; }
    @media (max-width: 991px) { .sticky-top { position: static; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('hotelSearch');
    const select = document.getElementById('hotelSelect');
    const checkBtn = document.getElementById('checkButton');
    const hotelsGrid = document.getElementById('hotelsGrid');
    const detailsPanel = document.getElementById('hotelDetailsPanel');
    const detailsContent = document.getElementById('detailsContent');
    const detailsEmpty = document.getElementById('detailsEmpty');
    const detailsLoading = document.getElementById('detailsLoading');

    // Filter hotel cards by search and select
    function filterHotels() {
        const q = searchInput.value.trim().toLowerCase();
        const selected = select.value;
        const cards = hotelsGrid.querySelectorAll('.hotel-card');

        cards.forEach(card => {
            const name = card.querySelector('.card-title').textContent.toLowerCase();
            const id = card.dataset.hotelId;
            const matchesSearch = !q || name.includes(q);
            const matchesSelect = selected === 'all' || selected === id;
            card.parentElement.style.display = (matchesSearch && matchesSelect) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterHotels);
    select.addEventListener('change', filterHotels);

    checkBtn.addEventListener('click', function () {
        // If 'all' selected, show all (already shown). If specific, open details for that hotel
        const hotelId = select.value;
        if (!hotelId) return;
        if (hotelId === 'all') {
            // scroll to grid
            hotelsGrid.scrollIntoView({ behavior: 'smooth' });
            // show all cards
            searchInput.value = '';
            filterHotels();
            return;
        }
        fetchHotelDetails(hotelId);
    });

    // Click on card view button
    hotelsGrid.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-view');
        if (!btn) return;
        const id = btn.dataset.id;
        fetchHotelDetails(id);
    });

    function showLoading(show) {
        detailsLoading.style.display = show ? 'block' : 'none';
        detailsEmpty.style.display = show ? 'none' : detailsEmpty.style.display;
        detailsContent.style.display = show ? 'none' : detailsContent.style.display;
    }

    function fetchHotelDetails(id) {
        if (!id) return;
        showLoading(true);
        fetch(`/get-hotel-details/${id}`)
            .then(res => res.json())
            .then(data => {
                showLoading(false);
                if (!data || !data.id) {
                    detailsEmpty.querySelector('p').textContent = 'कोई जानकारी नहीं मिली।';
                    detailsEmpty.style.display = 'block';
                    detailsContent.style.display = 'none';
                    return;
                }

                detailsEmpty.style.display = 'none';
                detailsContent.style.display = 'block';

                document.getElementById('detailHotelName').textContent = data.hotel_name || '';
                document.getElementById('detailIncharge').textContent = data.incharge_name ? `प्रभारी: ${data.incharge_name}` : '';
                document.getElementById('detailContact').textContent = data.contact_number || '—';
                document.getElementById('detailRooms').textContent = data.total_rooms || '—';

                const facilities = [];
                if (data.common_bath) facilities.push('कॉमन बाथ');
                if (data.lift) facilities.push('लिफ्ट');
                if (data.generator) facilities.push('जनरेटर');
                document.getElementById('detailFacilities').textContent = facilities.join(' • ') || 'कोई जानकारी नहीं';

                document.getElementById('detailAddress').textContent = data.address || '';

                // Directions link (Google Maps)
                const lat = data.latitude || null;
                const lng = data.longitude || null;
                const directionsLink = document.getElementById('directionsLink');
                const openMapBtn = document.getElementById('openMapBtn');
                if (lat && lng) {
                    directionsLink.href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                    openMapBtn.onclick = () => window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`, '_blank');
                    directionsLink.style.display = '';
                    openMapBtn.style.display = '';
                } else {
                    directionsLink.href = '#';
                    openMapBtn.style.display = 'none';
                }
            })
            .catch(err => {
                showLoading(false);
                detailsEmpty.querySelector('p').textContent = 'डिटेल्स लादने में त्रुटि हुई।';
                detailsEmpty.style.display = 'block';
                detailsContent.style.display = 'none';
                console.error(err);
            });
    }

    // Initialize filter on load
    filterHotels();
});
</script>

@endsection
