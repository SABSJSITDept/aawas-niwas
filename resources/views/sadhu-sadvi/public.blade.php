@extends('layouts.app')

@section('content')
<!-- Include Header -->
@include('includes.header')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --saffron:       #e65c00;
        --saffron-light: #ff8800;
        --maroon:        #7b1a1a;
        --navy:          #1a237e;
        --navy-mid:      #283593;
        --teal:          #00695c;
        --teal-light:    #00897b;
        --cream:         #fff8f0;
        --bg:            #f0f2f5;
        --card-bg:       #ffffff;
        --text:          #1c1c2e;
        --muted:         #607d8b;
        --border:        #e8ecf0;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg);
        font-family: 'Noto Sans Devanagari', 'Poppins', sans-serif;
        color: var(--text);
    }

    /* ─── PAGE WRAPPER ─────────────────────────────── */
    .ss-page { min-height: 100vh; padding-bottom: 80px; }

    /* ─── HERO BANNER ──────────────────────────────── */
    .ss-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 45%, #3949ab 100%);
        padding: 40px 0 65px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .ss-hero::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.06) 0%, transparent 55%),
            radial-gradient(ellipse at 80% 20%, rgba(255,136,0,0.12) 0%, transparent 50%);
        pointer-events: none;
    }
    .ss-hero::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0;
        height: 40px;
        background: var(--bg);
        clip-path: ellipse(55% 100% at 50% 100%);
    }
    .ss-hero-inner { position: relative; z-index: 1; }
    .ss-hero-org {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.22);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 5px 16px;
        font-size: 12px; font-weight: 600;
        color: rgba(255,255,255,0.92);
        letter-spacing: 0.6px;
        margin-bottom: 10px;
        animation: fadeDown .5s ease both;
    }
    .ss-hero-org i { color: var(--saffron-light); }
    .ss-hero-title {
        font-size: clamp(1.2rem, 3vw, 1.75rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        margin-bottom: 6px;
        animation: fadeUp .6s ease both;
        text-align: left;
    }
    .ss-hero-sub {
        font-size: clamp(.78rem, 1.4vw, .92rem);
        color: rgba(255,255,255,0.8);
        font-weight: 400;
        animation: fadeUp .8s ease both;
        text-align: left;
    }
    .ss-hero-row {
        display: flex;
        align-items: center;
        gap: 32px;
    }
    .ss-hero-left {
        flex: 1;
        text-align: left;
    }
    .ss-hero-right {
        flex: 0 0 340px;
        max-width: 340px;
    }
    @media (max-width: 767px) {
        .ss-hero-row { flex-direction: column; gap: 18px; }
        .ss-hero-right { flex: 1 1 100%; max-width: 100%; width: 100%; }
        .ss-hero-title, .ss-hero-sub { text-align: center; }
        .ss-hero-left { text-align: center; }
    }
    .ss-hero-title {
        font-size: clamp(1.2rem, 3vw, 1.75rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        margin-bottom: 6px;
        animation: fadeUp .6s ease both;
    }
    .ss-hero-sub {
        font-size: clamp(.78rem, 1.4vw, .92rem);
        color: rgba(255,255,255,0.8);
        font-weight: 400;
        animation: fadeUp .8s ease both;
    }

    /* ─── SEARCH BAR ───────────────────────────────── */
    .ss-search-wrap {
        margin-top: 0;
        max-width: 100%;
        width: 100%;
        position: relative;
        animation: fadeUp 1s ease both;
    }
    .ss-search-input {
        width: 100%;
        padding: 13px 50px 13px 20px;
        border-radius: 50px;
        border: 2px solid rgba(255,255,255,0.35) !important;
        background: rgba(255,255,255,0.15) !important;
        -webkit-backdrop-filter: blur(12px);
        backdrop-filter: blur(12px);
        color: #fff !important;
        font-size: 0.95rem;
        font-family: 'Noto Sans Devanagari', 'Poppins', sans-serif;
        font-weight: 500;
        outline: none !important;
        box-shadow: none !important;
        transition: border .25s, background .25s, box-shadow .25s;
        caret-color: #fff;
        -webkit-appearance: none;
        appearance: none;
    }
    .ss-search-input::placeholder { color: rgba(255,255,255,0.55) !important; }
    .ss-search-input:focus {
        border-color: rgba(255,255,255,0.75) !important;
        background: rgba(255,255,255,0.24) !important;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.15) !important;
        color: #fff !important;
    }
    .ss-search-icon {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.7);
        font-size: 1.15rem;
        pointer-events: none;
    }
    .ss-search-clear {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        display: none;
        line-height: 1;
    }
    .ss-search-clear:hover { color: #fff; }

    /* ─── NO SEARCH RESULTS ───────────────────────── */
    .ss-no-results {
        background: var(--card-bg);
        border-radius: 18px;
        padding: 60px 24px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid var(--border);
        display: none;
    }
    .ss-no-results i { font-size: 3rem; color: #b0bec5; display: block; margin-bottom: 14px; }
    .ss-no-results h6 { font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .ss-no-results p  { font-size: .88rem; color: var(--muted); margin: 0; }

    /* ─── SECTION TITLE ────────────────────────────── */
    .ss-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ss-section-title::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    /* ─── CARD ─────────────────────────────────────── */
    .sant-card {
        background: var(--card-bg);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: 1px solid var(--border);
        transition: transform .35s cubic-bezier(.4,0,.2,1),
                    box-shadow .35s cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .sant-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(26,35,126,0.14);
    }

    /* ── Card Header (deep navy, like app) ─────────── */
    .sc-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
        padding: 20px 20px 20px 20px;
        position: relative;
        min-height: 120px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .sc-num {
        min-width: 42px; height: 42px;
        background: rgba(255,255,255,0.18);
        border: 2px solid rgba(255,255,255,0.35);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .sc-title-wrap { flex: 1; }
    .sc-name {
        font-size: 1.02rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.45;
        margin-bottom: 8px;
    }
    .sc-date {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.65);
        font-weight: 500;
    }
    .sc-share-btn {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 20px;
        padding: 5px 13px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px;
        cursor: pointer;
        text-decoration: none;
        flex-shrink: 0;
        transition: background .2s;
        margin-top: 2px;
    }
    .sc-share-btn:hover { background: rgba(255,255,255,0.28); color: #fff; }

    /* ── Route Row (orange tint, like app) ──────────── */
    .sc-route {
        background: #fff8f0;
        border-top: 1px solid #ffe0b2;
        border-bottom: 1px solid #ffe0b2;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: var(--saffron);
        font-weight: 600;
        flex-wrap: wrap;
    }
    .sc-route-icon { font-size: 1.1rem; }
    .sc-route-arrow { color: #bdbdbd; font-size: 0.85rem; margin: 0 2px; }
    .sc-dist-badge {
        margin-left: auto;
        background: #fff3e0;
        border: 1px solid #ffcc80;
        border-radius: 12px;
        padding: 2px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--saffron);
    }

    /* ── Tags Row ────────────────────────────────────── */
    .sc-tags {
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--border);
    }
    .sc-tag {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .sc-tag-thana {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    .sc-tag-thana i { color: #388e3c; }
    .sc-tag-info {
        background: #ede7f6;
        color: #512da8;
        border: 1px solid #ce93d8;
    }
    .sc-tag-info i { color: #7b1fa2; }

    /* ── Location Section ───────────────────────────── */
    .sc-location {
        padding: 14px 18px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border-bottom: 1px solid var(--border);
    }
    .sc-loc-pin {
        width: 34px; height: 34px;
        background: #fde8e8;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #c62828;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .sc-loc-label {
        font-size: 0.72rem;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .sc-loc-value {
        font-size: 0.88rem;
        color: var(--text);
        font-weight: 600;
        line-height: 1.5;
    }

    /* ── Companions (साथ के संत) ────────────────────── */
    .sc-companions {
        padding: 14px 18px;
        flex-grow: 1;
    }
    .sc-companions-head {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--teal);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: flex; align-items: center; gap: 6px;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #b2dfdb;
    }
    .sc-companions-head i { font-size: 1rem; }
    .sc-sant-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .sc-sant-item:last-child { border-bottom: none; }
    .sc-sant-avatar {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #e0f2f1, #b2dfdb);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--teal);
        font-size: 1rem;
        flex-shrink: 0;
        border: 1.5px solid #b2dfdb;
    }
    .sc-sant-name {
        font-size: 0.85rem;
        color: var(--text);
        font-weight: 500;
        line-height: 1.4;
    }
    .sc-no-companions {
        font-size: 0.82rem;
        color: var(--muted);
        font-style: italic;
    }

    /* ── Card Footer ────────────────────────────────── */
    .sc-footer {
        padding: 14px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
    }
    .sc-btn-map {
        flex: 1;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        text-decoration: none;
        transition: all .25s ease;
        cursor: pointer;
    }
    .sc-btn-map:hover {
        background: linear-gradient(135deg, #283593, #3949ab);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(26,35,126,0.25);
    }
    .sc-btn-no-map {
        flex: 1;
        background: #f5f5f5;
        color: var(--muted);
        border: none;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.82rem;
        font-weight: 500;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        cursor: default;
    }

    /* ─── STATE CONTAINERS ──────────────────────────── */
    .ss-state {
        background: var(--card-bg);
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 70px 24px;
        text-align: center;
        max-width: 560px;
        margin: 0 auto;
    }
    .ss-state-icon { font-size: 3.5rem; margin-bottom: 18px; display: block; }
    .ss-spinner {
        width: 52px; height: 52px;
        border: 4px solid #e3e8ef;
        border-top-color: var(--navy);
        border-radius: 50%;
        animation: spin .8s linear infinite;
        margin: 0 auto 20px;
    }

    /* ─── ANIMATIONS ────────────────────────────────── */
    @keyframes fadeUp   { from { opacity:0; transform:translateY(28px) } to { opacity:1; transform:translateY(0) } }
    @keyframes fadeDown { from { opacity:0; transform:translateY(-20px) } to { opacity:1; transform:translateY(0) } }
    @keyframes spin     { to { transform: rotate(360deg) } }

    .card-enter { animation: fadeUp .45s ease both; }

    /* ─── RESPONSIVE ────────────────────────────────── */
    @media (max-width: 576px) {
        .sc-name { font-size: 0.96rem; }
        .sc-route { font-size: 0.75rem; }
    }
</style>

<div class="ss-page">

    <!-- Hero Banner -->
    <div class="ss-hero">
        <div class="container ss-hero-inner">
            <div class="ss-hero-row">
                <!-- Left: Text -->
                <div class="ss-hero-left">
                    <div class="ss-hero-org">
                        <i class="bi bi-building-fill"></i>
                        आवास निवास &nbsp;·&nbsp; श्री जैन संघ
                    </div>
                    <h1 class="ss-hero-title">संत-सतिया जी का विश्राम स्थल</h1>
                    <p class="ss-hero-sub">साधु-साध्वी भगवंतों के प्रवास एवं दर्शन की पावन जानकारी</p>
                </div>
                <!-- Right: Search -->
                <div class="ss-hero-right">
                    <div class="ss-search-wrap">
                        <input
                            type="text"
                            id="searchInput"
                            class="ss-search-input"
                            placeholder="नाम से खोजें… (Search by name)"
                            oninput="handleSearch(this.value)"
                            autocomplete="off"
                            spellcheck="false"
                        >
                        <i class="bi bi-search ss-search-icon" id="searchIconDefault"></i>
                        <button class="ss-search-clear" id="searchClearBtn" onclick="clearSearch()" title="Clear">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="margin-top: 40px;">

        <!-- Loading -->
        <div id="loadingState" class="ss-state">
            <div class="ss-spinner"></div>
            <h5 class="fw-bold mb-1">जानकारी लोड हो रही है…</h5>
            <p class="text-muted mb-0" style="font-size:.9rem;">कृपया प्रतीक्षा करें</p>
        </div>

        <!-- Cards Grid -->
        <div id="cardsContainer" style="display:none;">
            <p class="ss-section-title" id="cardCount">
                <i class="bi bi-patch-check-fill text-primary"></i>
                <span>संत-सतिया जी की सूची</span>
            </p>
            <div class="row g-4" id="sadhuGrid"></div>

            <!-- No search results -->
            <div class="ss-no-results" id="noResultsState">
                <i class="bi bi-search"></i>
                <h6>कोई परिणाम नहीं मिला</h6>
                <p>"<span id="noResultsQuery"></span>" से कोई संत नहीं मिले। कृपया अलग नाम से खोजें।</p>
            </div>
        </div>

        <!-- Error -->
        <div id="errorState" class="ss-state" style="display:none;">
            <span class="ss-state-icon text-danger"><i class="bi bi-wifi-off"></i></span>
            <h5 class="fw-bold text-danger mb-2">सर्वर से संपर्क नहीं हो सका</h5>
            <p class="text-muted mb-4" style="font-size:.9rem;">कृपया पृष्ठ को रीफ्रेश करें और पुनः प्रयास करें।</p>
            <button class="btn btn-danger rounded-pill px-5 fw-semibold" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-2"></i>रीफ्रेश करें
            </button>
        </div>

        <!-- Empty -->
        <div id="emptyState" class="ss-state" style="display:none;">
            <span class="ss-state-icon text-secondary"><i class="bi bi-inbox"></i></span>
            <h5 class="fw-bold mb-2">कोई रिकॉर्ड नहीं</h5>
            <p class="text-muted mb-0" style="font-size:.9rem;">वर्तमान में संत-सतिया जी की कोई जानकारी उपलब्ध नहीं है।</p>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', loadSadhuSadvi);

function loadSadhuSadvi() {
    fetch('/api/sadhu-sadvi')
        .then(r => r.json())
        .then(data => {
            document.getElementById('loadingState').style.display = 'none';
            if (data.success && data.data && data.data.length > 0) {
                allData = data.data;
                renderCards(data.data);
                document.getElementById('cardsContainer').style.display = 'block';
            } else if (!data.success) {
                document.getElementById('errorState').style.display = 'block';
            } else {
                document.getElementById('emptyState').style.display = 'block';
            }
        })
        .catch(() => {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'block';
        });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderCards(data, updateCount = true) {
    const grid = document.getElementById('sadhuGrid');
    if (updateCount) {
        document.getElementById('cardCount').innerHTML =
            '<i class="bi bi-patch-check-fill text-primary"></i> <span>संत-सतिया जी की सूची &nbsp;(' + data.length + ')</span>';
    }

    grid.innerHTML = data.map((item, i) => {
        const delay = (i % 6) * 0.08;
        const thanaCount = item.thana || 0;
        const companions = item.thana_sants || [];
        const address = item.address ? escapeHtml(item.address).replace(/\n/g, '<br>') : null;

        // Route row: show address snippet
        const routeSnippet = item.address
            ? escapeHtml(item.address.split('\n')[0])
            : 'विराजने का स्थान';

        // Share data
        const shareData = encodeURIComponent(item.name);

        const companionsHtml = companions.length > 0
            ? companions.map(s => `
                <div class="sc-sant-item">
                    <div class="sc-sant-avatar"><i class="bi bi-person-fill"></i></div>
                    <span class="sc-sant-name">${escapeHtml(s.sant_name)}</span>
                </div>`).join('')
            : `<p class="sc-no-companions"><i class="bi bi-info-circle me-1"></i>जानकारी उपलब्ध नहीं है</p>`;

        const footerBtn = item.link
            ? `<a href="${escapeHtml(item.link)}" target="_blank" rel="noopener noreferrer" class="sc-btn-map">
                    <i class="bi bi-geo-alt-fill"></i> मार्गदर्शन देखें
               </a>`
            : `<span class="sc-btn-no-map"><i class="bi bi-geo-alt"></i> लिंक उपलब्ध नहीं</span>`;

        return `
        <div class="col-12 col-md-6 col-xl-4 card-enter" style="animation-delay:${delay}s">
          <div class="sant-card">

            <!-- Header: Navy gradient with name + number -->
            <div class="sc-header">
              <div class="sc-num">${i + 1}</div>
              <div class="sc-title-wrap">
                <div class="sc-name">${escapeHtml(item.name)}</div>
                <div class="sc-date"><i class="bi bi-calendar3 me-1"></i>${new Date().toLocaleDateString('hi-IN', {day:'2-digit',month:'short',year:'numeric'})}</div>
              </div>
              <button class="sc-share-btn" onclick="shareSant('${shareData}', '${escapeHtml(item.address || '')}')">
                <i class="bi bi-share-fill"></i> शेयर
              </button>
            </div>

            <!-- Route row (orange) -->
            <div class="sc-route">
              <i class="bi bi-person-walking sc-route-icon"></i>
              <span>${routeSnippet}</span>
              <span class="sc-route-arrow"><i class="bi bi-arrow-right"></i></span>
              <span>विश्राम स्थल</span>
              <span class="sc-dist-badge">स्थानीय</span>
            </div>

            <!-- Tags row -->
            <div class="sc-tags">
              ${thanaCount > 0
                ? `<span class="sc-tag sc-tag-thana"><i class="bi bi-building"></i> ठाणा ${thanaCount}</span>`
                : ''}
              ${companions.length > 0
                ? `<span class="sc-tag sc-tag-info"><i class="bi bi-people-fill"></i> ${companions.length} संत साथ में</span>`
                : ''}
            </div>

            <!-- Location pin -->
            ${address ? `
            <div class="sc-location">
              <div class="sc-loc-pin"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <div class="sc-loc-label">विराजने का स्थान</div>
                <div class="sc-loc-value">${address}</div>
              </div>
            </div>` : ''}

            <!-- Companion Saints -->
            <div class="sc-companions">
              <div class="sc-companions-head">
                <i class="bi bi-people-fill"></i> साथ के संत
              </div>
              ${companionsHtml}
            </div>

            <!-- Footer -->
            <div class="sc-footer">
              ${footerBtn}
            </div>

          </div>
        </div>`;
    }).join('');
}

// ─── SEARCH ─────────────────────────────────────────────
let allData = [];

function handleSearch(query) {
    const clearBtn = document.getElementById('searchClearBtn');
    const defaultIcon = document.getElementById('searchIconDefault');
    const q = query.trim().toLowerCase();

    clearBtn.style.display  = q.length ? 'block' : 'none';
    defaultIcon.style.display = q.length ? 'none'  : 'block';

    if (!allData.length) return;

    const filtered = q
        ? allData.filter(item => {
            // Match main saint name
            if (item.name.toLowerCase().includes(q)) return true;
            // Match companion saints (thana_sants)
            const companions = item.thana_sants || [];
            return companions.some(s => s.sant_name && s.sant_name.toLowerCase().includes(q));
        })
        : allData;

    renderCards(filtered, false);

    const noRes = document.getElementById('noResultsState');
    if (filtered.length === 0) {
        noRes.style.display = 'block';
        document.getElementById('noResultsQuery').textContent = query.trim();
    } else {
        noRes.style.display = 'none';
    }
}

function clearSearch() {
    const input = document.getElementById('searchInput');
    input.value = '';
    input.focus();
    handleSearch('');
}
// ─────────────────────────────────────────────────────────

function shareSant(nameEncoded, address) {
    const name = decodeURIComponent(nameEncoded);
    const text = `🙏 ${name}\n📍 ${address || 'विराजने का स्थान'}\n\nसाधु-साध्वी विश्राम स्थल जानकारी`;
    if (navigator.share) {
        navigator.share({ title: name, text: text, url: window.location.href })
            .catch(() => {});
    } else {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:28px;left:50%;transform:translateX(-50%);background:#1a237e;color:#fff;padding:10px 22px;border-radius:24px;font-size:14px;font-weight:600;z-index:9999;box-shadow:0 6px 20px rgba(0,0,0,0.25)';
            toast.innerHTML = '<i class="bi bi-clipboard-check me-2"></i>जानकारी कॉपी हो गई!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2800);
        }).catch(() => {});
    }
}
</script>

@includeIf('includes.footer')
@endsection
