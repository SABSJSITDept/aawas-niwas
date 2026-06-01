@extends('admin.layout')

@section('content')
<div class="container my-5">
    <!-- Header / Hero -->
    <div class="d-flex align-items-center justify-content-between bg-white rounded-4 p-4 shadow-sm mb-4">
        <div>
            <h4 class="mb-1">🏨 होटल के कमरे की सुविधाएं</h4>
            <p class="text-muted mb-0">होटल चुनें और कमरे की सुविधाओं को सुंदर, फ़िल्टर करने योग्य कार्ड्स में देखें।</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <input id="featureSearch" type="search" class="form-control form-control-sm" placeholder="कमरा संख्या या सुविधा खोजें..." style="min-width:220px">
            <select id="hotelSelect" class="form-select form-select-sm">
                <option value="">-- होटल चुनें --</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->hotel_name }}</option>
                @endforeach
            </select>
            <button id="loadFeaturesBtn" class="btn btn-primary btn-sm">लोड करें</button>
            <a href="#" id="downloadLink" class="btn btn-success btn-sm d-none">📥 एक्सेल</a>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <div class="col-lg-8">
            <div id="featureControls" class="d-flex gap-2 mb-3">
                <button id="viewGrid" class="btn btn-outline-secondary btn-sm active">Grid</button>
                <button id="viewList" class="btn btn-outline-secondary btn-sm">List</button>
                <div class="ms-auto text-muted small align-self-center" id="featuresCount">0 features</div>
            </div>

            <div id="featureDetails" class="row g-3">
                <!-- Feature cards will render here -->
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm p-3 sticky-top" style="top:20px;">
                <h5 class="mb-3">चुने हुए कमरे का विवरण</h5>
                <div id="sideEmpty" class="text-center text-muted py-5">
                    <i class="bi bi-info-circle display-6"></i>
                    <div class="mt-2">कोई कमरा चुना नहीं गया। किसी कार्ड पर क्लिक करें।</div>
                </div>

                <div id="sideContent" style="display:none;">
                    <h6 id="sideRoomNumber"></h6>
                    <p class="mb-1"><strong>AC:</strong> <span id="sideAc"></span></p>
                    <p class="mb-1"><strong>Attach Bath:</strong> <span id="sideAttach"></span></p>
                    <p class="mb-1"><strong>Toilet Type:</strong> <span id="sideToilet"></span></p>
                    <p class="mb-1"><strong>Other:</strong> <span id="sideOther"></span></p>
                    <div class="d-grid mt-3">
                        <a id="sideCall" href="#" class="btn btn-outline-primary btn-sm">Call Hotel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .feature-card { border-radius:12px; transition: transform .16s ease, box-shadow .16s ease; }
    .feature-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(15,23,42,0.06); }
    .feature-badge { font-size:12px; }
    .sticky-top { position: sticky; }
    @media (max-width: 991px) { .sticky-top { position: static; } }
</style>

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

        features.forEach(f => {
            const matchesSearch = !q || (`${f.room_number}`.toLowerCase().includes(q) || (f.toilet_type || '').toLowerCase().includes(q));
            if (!matchesSearch) return;

            visible++;

            const cardHtml = `
                <div class="${currentView === 'grid' ? 'col-md-6' : 'col-12'}">
                    <div class="card feature-card h-100" data-room-id="${f.id}" data-contact="${f.contact || ''}">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Room ${f.room_number}</h6>
                                    <div class="small text-muted">${f.room_type || '—'}</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary feature-badge">${f.status || 'Available'}</span>
                                </div>
                            </div>

                            <div class="mt-3 small text-muted">
                                <div><strong>AC:</strong> ${f.ac == 1 ? 'हाँ' : 'नहीं'}</div>
                                <div><strong>Attach Bath:</strong> ${f.attach_bath == 1 ? 'हाँ' : 'नहीं'}</div>
                                <div><strong>Toilet:</strong> ${f.toilet_type || '—'}</div>
                            </div>

                            <div class="mt-auto d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary btn-select">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', cardHtml);
        });

        featuresCount.textContent = `${visible} features`;
        if (visible === 0) container.innerHTML = '<div class="text-muted">कोई फीचर नहीं मिला।</div>';
    }

    function setDownloadLink(hotelId) {
        downloadLink.href = `/admin/export-room-features/${hotelId}`;
        downloadLink.classList.remove('d-none');
    }

    loadBtn.addEventListener('click', function () {
        const hotelId = hotelSelect.value;
        container.innerHTML = '';
        downloadLink.classList.add('d-none');
        document.getElementById('sideContent').style.display = 'none';
        document.getElementById('sideEmpty').style.display = 'block';

        if (!hotelId) {
            container.innerHTML = '<div class="text-warning">कृपया पहले कोई होटल चुनें।</div>';
            features = [];
            renderCards();
            return;
        }

        setDownloadLink(hotelId);

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
                container.innerHTML = '<div class="text-danger">डेटा लोड करने में त्रुटि हुई।</div>';
            });
    });

    // Search and view toggles
    searchInput.addEventListener('input', renderCards);
    viewGrid.addEventListener('click', function () { currentView = 'grid'; viewGrid.classList.add('active'); viewList.classList.remove('active'); renderCards(); });
    viewList.addEventListener('click', function () { currentView = 'list'; viewList.classList.add('active'); viewGrid.classList.remove('active'); renderCards(); });

    // Delegate click for selecting a room card
    container.addEventListener('click', function (e) {
        const sel = e.target.closest('.btn-select');
        if (!sel) return;
        const card = sel.closest('.card');
        const roomId = card.dataset.roomId;
        const feature = features.find(f => String(f.id) === String(roomId));
        if (!feature) return;

        document.getElementById('sideEmpty').style.display = 'none';
        document.getElementById('sideContent').style.display = 'block';
        document.getElementById('sideRoomNumber').textContent = `Room ${feature.room_number}`;
        document.getElementById('sideAc').textContent = feature.ac == 1 ? 'हाँ' : 'नहीं';
        document.getElementById('sideAttach').textContent = feature.attach_bath == 1 ? 'हाँ' : 'नहीं';
        document.getElementById('sideToilet').textContent = feature.toilet_type || '—';
        document.getElementById('sideOther').textContent = feature.room_type || '—';
        const callLink = document.getElementById('sideCall');
        if (feature.contact) {
            callLink.href = `tel:${feature.contact}`;
            callLink.style.display = '';
        } else {
            callLink.style.display = 'none';
        }
    });
});
</script>

@endsection
