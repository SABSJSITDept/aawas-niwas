@extends('admin.layout')

@section('title', 'Bhojanshala - Expected Members')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="bhojan-header card shadow-sm border-0 rounded-3 mb-4 animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h2 class="display-6 text-white mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-cup-hot-fill"></i>
                        भोजनशाला व्यवस्था
                    </h2>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-calendar-event me-2"></i>Select a date to view expected guests
                    </p>
                </div>
                <div class="text-end">
                    <div class="badge bg-white text-primary fs-5 px-3 py-2" id="selectedDateDisplay">
                        <i class="bi bi-calendar3"></i> --
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="card mb-4 shadow-sm border-0 rounded-3 animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="datePicker" class="form-label fw-semibold">
                        <i class="bi bi-calendar-check text-primary"></i> Select Date
                    </label>
                    <input type="date" id="datePicker" class="form-control form-control-lg shadow-sm" />
                </div>

                <div class="col-md-8 d-flex flex-wrap gap-2 justify-content-end">
                    <button id="btnLoad" class="btn btn-primary btn-lg px-4 shadow-sm">
                        <i class="bi bi-play-fill"></i> Load
                    </button>
                    <button id="btnToday" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-calendar-day"></i> Today
                    </button>
                    <button id="btnTomorrow" class="btn btn-outline-info btn-lg px-4">
                        <i class="bi bi-calendar-plus"></i> Tomorrow
                    </button>
                    <button id="btnRefresh" class="btn btn-outline-secondary btn-lg px-3" title="Refresh">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 bhojan-card animate__animated animate__fadeInLeft">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h4 class="mb-0 bhojan-title d-flex align-items-center gap-2">
                                <i class="bi bi-journal-text text-warning"></i>
                                भोजन व्यवस्था रिपोर्ट
                            </h4>
                        </div>
                        <div class="badge bg-light text-dark px-3 py-2 fs-6" id="cardDateDisplay">
                            <i class="bi bi-calendar3"></i> --
                        </div>
                    </div>

                    <div id="bhojanContent" class="bhojan-content">
                        <!-- dynamic content lines injected here -->
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-week fs-1 mb-3 d-block"></i>
                            <p>Select date and click Load button</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 animate__animated animate__fadeInRight">
                <div class="card-body p-4">
                    <h5 class="mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-pie-chart-fill text-primary"></i>
                        Summary
                    </h5>
                    
                    <div class="summary-item breakfast-item mb-3 p-3 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sunrise-fill text-warning fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Breakfast</div>
                                    <small class="text-muted">08:00 AM</small>
                                </div>
                            </div>
                            <div class="badge bg-warning text-dark fs-5 px-3 py-2" id="sumBreakfast">0</div>
                        </div>
                    </div>

                    <div class="summary-item lunch-item mb-3 p-3 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sun-fill text-danger fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Lunch</div>
                                    <small class="text-muted">01:00 PM</small>
                                </div>
                            </div>
                            <div class="badge bg-danger fs-5 px-3 py-2" id="sumLunch">0</div>
                        </div>
                    </div>

                    <div class="summary-item evening-item mb-3 p-3 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sunset-fill text-primary fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Evening</div>
                                    <small class="text-muted">06:00 PM</small>
                                </div>
                            </div>
                            <div class="badge bg-primary fs-5 px-3 py-2" id="sumEvening">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />

</div>

<style>
    /* Header Gradient */
    .bhojan-header {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #fbb040 100%);
        border: none;
    }

    .bhojan-header .text-white {
        color: white !important;
    }

    .bhojan-header .text-white-50 {
        color: rgba(255,255,255,0.8) !important;
    }

    /* Card Styles */
    .bhojan-card { 
        background: #fff; 
        border: none;
        transition: all 0.3s ease;
    }

    .bhojan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }

    .bhojan-title { 
        font-weight: 700; 
        letter-spacing: 0.5px; 
        font-size: 1.25rem;
        color: #2c3e50;
    }

    .bhojan-content {
        margin-top: 1rem;
        height: 400px;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    /* Content Message */
    .bhojan-message { 
        display: block; 
        line-height: 2.2; 
        font-size: 1.1rem;
        padding: 24px;
        background: white;
        border-radius: 12px;
        border-left: 6px solid #ff6b35;
        color: #2c3e50;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.1);
        font-family: 'Noto Sans Devanagari', 'Hind', Arial, sans-serif;
    }

    .bhojan-line { 
        display: block; 
        text-transform: uppercase; 
        line-height: 1.8; 
        margin-bottom: 12px; 
        font-size: 0.95rem;
        padding: 8px 12px;
        background: white;
        border-radius: 8px;
        border-left: 4px solid #ff6b35;
        color: #2c3e50;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .bhojan-line:hover {
        background: #fff5f2;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(255, 107, 53, 0.1);
    }

    .bhojan-small { 
        font-size: 0.85rem; 
        color: #6b7280; 
        margin-top: 12px;
        padding: 8px;
        background: white;
        border-radius: 6px;
    }

    /* Scrollbar */
    .bhojan-content::-webkit-scrollbar { 
        width: 10px; 
    }

    .bhojan-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .bhojan-content::-webkit-scrollbar-thumb { 
        background: linear-gradient(135deg, #ff6b35, #fbb040);
        border-radius: 10px;
    }

    .bhojan-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #e55a2b, #e9a036);
    }

    /* Summary Items */
    .summary-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .summary-item:hover {
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(5px);
    }

    .breakfast-item {
        border-left: 4px solid #ffc107;
    }

    .lunch-item {
        border-left: 4px solid #dc3545;
    }

    .evening-item {
        border-left: 4px solid #0d6efd;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0b5ed7, #0a58ca);
    }

    /* Form Controls */
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
    }

    /* Badges */
    .badge {
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Animation for loading */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .loading {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bhojan-content {
            height: 300px;
        }

        .bhojan-title {
            font-size: 1rem;
        }

        .bhojan-line {
            font-size: 0.85rem;
            padding: 6px 10px;
        }
    }
</style>

<script>
(function(){
    const defaultSlots = ['08:00','13:00','18:00'];

    function formatDateInputValue(d) {
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth()+1).padStart(2,'0');
        const dd = String(d.getDate()).padStart(2,'0');
        return `${yyyy}-${mm}-${dd}`;
    }

    function formatDisplayDateForCard(iso) {
        // show DD-MM-YYYY KO (as in your example)
        const [y,m,d] = iso.split('-');
        return `${d}-${m}-${y} KO`;
    }

    const datePicker = document.getElementById('datePicker');
    const btnLoad = document.getElementById('btnLoad');
    const btnToday = document.getElementById('btnToday');
    const btnTomorrow = document.getElementById('btnTomorrow');
    const btnRefresh = document.getElementById('btnRefresh');

    const bhojanContent = document.getElementById('bhojanContent');
    const cardDateDisplay = document.getElementById('cardDateDisplay');

    const sumBreakfastEl = document.getElementById('sumBreakfast');
    const sumLunchEl = document.getElementById('sumLunch');
    const sumEveningEl = document.getElementById('sumEvening');

    const today = new Date();
    datePicker.value = formatDateInputValue(today);
    document.getElementById('selectedDateDisplay').textContent = formatDisplayDateForCard(formatDateInputValue(today));

    function buildCardText(dateIso, slotsObj) {
        // get counts
        const breakfast = slotsObj && slotsObj['08:00'] ? slotsObj['08:00'].total : 0;
        const lunch = slotsObj && slotsObj['13:00'] ? slotsObj['13:00'].total : 0;
        const evening = slotsObj && slotsObj['18:00'] ? slotsObj['18:00'].total : 0;
        const total = breakfast + lunch + evening;

        // Build single message in Hindi (Devanagari)
        let message = `जय गुरु नाना जय गुरु राम\n\n`;
        message += `तारीख : ${formatDisplayDateForCard(dateIso)}\n\n`;
        message += `सुबह में ${breakfast} अतिथियों के नाश्ते की व्यवस्था, `;
        message += `${lunch} अतिथियों के दिन के भोजन की व्यवस्था और `;
        message += `${evening} अतिथियों के शाम के भोजन की व्यवस्था करनी है।`;

        return { message, counts: { breakfast, lunch, evening, total } };
    }

    function renderCard(dateIso, slotsObj) {
        const { message, counts } = buildCardText(dateIso, slotsObj);

        // Animate count updates
        animateCount(sumBreakfastEl, counts.breakfast);
        animateCount(sumLunchEl, counts.lunch);
        animateCount(sumEveningEl, counts.evening);

        // update date shown on card top-right and header
        const displayDate = formatDisplayDateForCard(dateIso);
        cardDateDisplay.textContent = displayDate;
        document.getElementById('selectedDateDisplay').textContent = displayDate;

        // clear and fill bhojanContent with single message
        bhojanContent.innerHTML = '';
        
        const messageBox = document.createElement('div');
        messageBox.className = 'bhojan-message animate__animated animate__fadeIn';
        messageBox.style.whiteSpace = 'pre-line';
        messageBox.textContent = message;
        bhojanContent.appendChild(messageBox);

        // small muted footer with timestamp
        const footer = document.createElement('div');
        footer.className = 'bhojan-small animate__animated animate__fadeIn';
        footer.style.marginTop = '20px';
        footer.innerHTML = `<i class="bi bi-info-circle"></i> Last updated: ${new Date().toLocaleTimeString('en-IN')}`;
        bhojanContent.appendChild(footer);
    }

    // Animate counting function
    function animateCount(element, target) {
        const current = parseInt(element.textContent) || 0;
        const increment = target > current ? 1 : -1;
        const duration = 500;
        const steps = Math.abs(target - current);
        const stepDuration = steps > 0 ? duration / steps : 0;

        let count = current;
        const timer = setInterval(() => {
            count += increment;
            element.textContent = count;
            
            if ((increment > 0 && count >= target) || (increment < 0 && count <= target)) {
                element.textContent = target;
                clearInterval(timer);
                element.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => {
                    element.classList.remove('animate__animated', 'animate__pulse');
                }, 500);
            }
        }, stepDuration);
    }

    async function loadForDate(dateStr) {
        bhojanContent.innerHTML = `
            <div class="text-center text-primary py-5 loading">
                <div class="spinner-border mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p><i class="bi bi-hourglass-split"></i> Loading expected members for ${dateStr}...</p>
            </div>`;
        
        try {
            const url = `/api/bhojanshala/expected-members?date=${encodeURIComponent(dateStr)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error(`API returned ${res.status}`);
            const json = await res.json();

            if (json.status !== 'success') {
                throw new Error('API error');
            }

            renderCard(json.date, json.slots);
        } catch (err) {
            bhojanContent.innerHTML = `
                <div class="text-center text-danger p-4">
                    <i class="bi bi-exclamation-triangle-fill fs-1 mb-3 d-block"></i>
                    <h5>Failed to load data</h5>
                    <p class="text-muted">${err.message}</p>
                    <button class="btn btn-outline-primary mt-3" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Retry
                    </button>
                </div>`;
            // set zeros with animation
            animateCount(sumBreakfastEl, 0);
            animateCount(sumLunchEl, 0);
            animateCount(sumEveningEl, 0);
            cardDateDisplay.textContent = formatDisplayDateForCard(dateStr);
        }
    }

    btnLoad.addEventListener('click', () => {
        const date = datePicker.value;
        if (!date) {
            // Show alert with icon
            bhojanContent.innerHTML = `
                <div class="text-center text-warning p-4">
                    <i class="bi bi-exclamation-circle-fill fs-1 mb-3 d-block"></i>
                    <h5>कृपया तारीख चुनें</h5>
                    <p class="text-muted">Please select a date first</p>
                </div>`;
            return;
        }
        loadForDate(date);
    });

    btnToday.addEventListener('click', () => {
        const d = new Date();
        datePicker.value = formatDateInputValue(d);
        btnToday.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => btnToday.classList.remove('animate__animated', 'animate__pulse'), 500);
        loadForDate(datePicker.value);
    });

    btnTomorrow.addEventListener('click', () => {
        const t = new Date();
        t.setDate(t.getDate() + 1);
        datePicker.value = formatDateInputValue(t);
        btnTomorrow.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => btnTomorrow.classList.remove('animate__animated', 'animate__pulse'), 500);
        loadForDate(datePicker.value);
    });

    btnRefresh.addEventListener('click', () => {
        btnRefresh.classList.add('animate__animated', 'animate__rotateIn');
        setTimeout(() => btnRefresh.classList.remove('animate__animated', 'animate__rotateIn'), 500);
        loadForDate(datePicker.value);
    });

    // Auto update when date changes
    datePicker.addEventListener('change', () => {
        document.getElementById('selectedDateDisplay').textContent = formatDisplayDateForCard(datePicker.value);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && document.activeElement === datePicker) {
            btnLoad.click();
        }
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            btnRefresh.click();
        }
    });

    // initial load with fade in animation
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            loadForDate(datePicker.value);
        }, 300);
    });
})();
</script>

@endsection
