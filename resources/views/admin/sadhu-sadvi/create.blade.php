@extends('admin.layout')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #3730a3;
        --primary-light: #eef2ff;
        --success: #059669;
        --success-light: #ecfdf5;
        --danger: #dc2626;
        --danger-light: #fef2f2;
        --surface: #ffffff;
        --surface-alt: #f8fafc;
        --border: #e2e8f0;
        --border-focus: #4f46e5;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --radius: 12px;
        --radius-sm: 8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.08),0 1px 2px rgba(0,0,0,0.06);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.08),0 2px 6px rgba(0,0,0,0.05);
        --shadow-lg: 0 10px 40px rgba(79,70,229,0.12),0 4px 16px rgba(0,0,0,0.06);
        --transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .page-wrapper { background: linear-gradient(135deg,#f0f4ff 0%,#fafbff 50%,#f0fdf4 100%); min-height:100vh; padding:2rem 0; }
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .page-header-left { display:flex; align-items:center; gap:1rem; }
    .page-header-icon { width:52px; height:52px; background:linear-gradient(135deg,var(--primary),#7c3aed); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(79,70,229,0.35); }
    .page-header-icon i { color:#fff; font-size:1.25rem; }
    .page-title { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0; letter-spacing:-0.3px; }
    .page-subtitle { font-size:0.875rem; color:var(--text-secondary); margin:0; }
    .btn-back { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius-sm); color:var(--text-secondary); font-weight:500; font-size:0.875rem; text-decoration:none; transition:var(--transition); box-shadow:var(--shadow-sm); }
    .btn-back:hover { background:var(--primary-light); border-color:var(--primary); color:var(--primary); text-decoration:none; }
    .form-card { background:var(--surface); border-radius:var(--radius); border:1px solid var(--border); box-shadow:var(--shadow-lg); overflow:hidden; }
    .form-card-header { padding:1.5rem 2rem; background:linear-gradient(135deg,var(--primary) 0%,#7c3aed 100%); display:flex; align-items:center; gap:0.875rem; }
    .form-card-header-icon { width:40px; height:40px; background:rgba(255,255,255,0.2); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(8px); }
    .form-card-header-icon i { color:#fff; font-size:1rem; }
    .form-card-title { color:#fff; font-size:1.05rem; font-weight:600; margin:0; }
    .form-card-subtitle { color:rgba(255,255,255,0.75); font-size:0.8rem; margin:0; }
    .form-card-body { padding:2rem; }
    .field-group { margin-bottom:1.5rem; }
    .field-label { display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:0.5rem; }
    .label-icon { width:20px; height:20px; background:var(--primary-light); border-radius:4px; display:inline-flex; align-items:center; justify-content:center; }
    .label-icon i { color:var(--primary); font-size:0.65rem; }
    .required-badge { display:inline-flex; align-items:center; padding:1px 6px; background:var(--danger-light); color:var(--danger); border-radius:20px; font-size:0.65rem; font-weight:700; letter-spacing:0.5px; margin-left:auto; }
    .optional-badge { display:inline-flex; align-items:center; padding:1px 6px; background:var(--surface-alt); color:var(--text-muted); border-radius:20px; font-size:0.65rem; font-weight:600; letter-spacing:0.5px; margin-left:auto; }
    .form-field { width:100%; padding:0.75rem 1rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-size:0.9375rem; color:var(--text-primary); background:var(--surface); transition:var(--transition); outline:none; font-family:inherit; appearance:none; box-sizing:border-box; }
    .form-field::placeholder { color:var(--text-muted); }
    .form-field:focus { border-color:var(--border-focus); box-shadow:0 0 0 3px rgba(79,70,229,0.12); background:#fafbff; }
    .form-field.is-invalid { border-color:var(--danger); box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
    textarea.form-field { resize:vertical; min-height:90px; }
    .field-hint { display:flex; align-items:center; gap:0.35rem; margin-top:0.4rem; font-size:0.78rem; color:var(--text-muted); }
    .field-hint i { font-size:0.7rem; }
    .invalid-msg { display:flex; align-items:center; gap:0.375rem; margin-top:0.4rem; font-size:0.8rem; color:var(--danger); font-weight:500; }
    .section-divider { display:flex; align-items:center; gap:1rem; margin:1.75rem 0; }
    .section-divider::before,.section-divider::after { content:''; flex:1; height:1px; background:var(--border); }
    .section-divider-label { font-size:0.75rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.8px; white-space:nowrap; }
    .thana-panel { background:var(--surface-alt); border:1.5px solid var(--border); border-radius:var(--radius); overflow:hidden; animation:slideDown 0.25s ease; margin-bottom:1.5rem; }
    @keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
    .thana-panel-header { display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; background:linear-gradient(135deg,#0ea5e9 0%,#0284c7 100%); }
    .thana-panel-header-icon { width:32px; height:32px; background:rgba(255,255,255,0.2); border-radius:6px; display:flex; align-items:center; justify-content:center; }
    .thana-panel-header-icon i { color:#fff; font-size:0.85rem; }
    .thana-panel-title { color:#fff; font-size:0.9rem; font-weight:600; margin:0; }
    .thana-panel-count { margin-left:auto; background:rgba(255,255,255,0.25); color:#fff; padding:2px 10px; border-radius:20px; font-size:0.78rem; font-weight:600; }
    .thana-panel-body { padding:1.25rem; display:flex; flex-direction:column; gap:0.875rem; }
    .thana-name-row { display:flex; align-items:center; gap:0.75rem; }
    .thana-index-badge { width:32px; height:32px; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700; flex-shrink:0; }
    .thana-name-input { flex:1; padding:0.625rem 0.875rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-size:0.9rem; color:var(--text-primary); background:var(--surface); transition:var(--transition); outline:none; font-family:inherit; }
    .thana-name-input::placeholder { color:var(--text-muted); }
    .thana-name-input:focus { border-color:#0ea5e9; box-shadow:0 0 0 3px rgba(14,165,233,0.12); }
    .action-bar { display:flex; align-items:center; gap:0.75rem; padding-top:1.5rem; border-top:1px solid var(--border); margin-top:0.5rem; flex-wrap:wrap; }
    .btn-submit { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 2rem; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border:none; border-radius:var(--radius-sm); font-size:0.9375rem; font-weight:600; cursor:pointer; transition:var(--transition); box-shadow:0 4px 14px rgba(79,70,229,0.35); letter-spacing:0.1px; }
    .btn-submit:hover:not(:disabled) { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,0.45); }
    .btn-submit:active:not(:disabled) { transform:translateY(0); }
    .btn-submit:disabled { opacity:0.65; cursor:not-allowed; }
    .btn-cancel { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.5rem; background:var(--surface); color:var(--text-secondary); border:1.5px solid var(--border); border-radius:var(--radius-sm); font-size:0.9375rem; font-weight:500; text-decoration:none; transition:var(--transition); }
    .btn-cancel:hover { background:var(--surface-alt); color:var(--text-primary); text-decoration:none; }
    .action-info { margin-left:auto; font-size:0.78rem; color:var(--text-muted); display:flex; align-items:center; gap:0.35rem; }
    .info-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden; margin-bottom:1.25rem; }
    .info-card-header { padding:1rem 1.25rem; background:linear-gradient(135deg,#0ea5e9,#0284c7); display:flex; align-items:center; gap:0.625rem; }
    .info-card-header i { color:#fff; font-size:0.9rem; }
    .info-card-header-text { color:#fff; font-size:0.875rem; font-weight:600; }
    .info-card-body { padding:1.25rem; }
    .info-item { display:flex; align-items:flex-start; gap:0.625rem; padding:0.625rem 0; border-bottom:1px solid var(--border); font-size:0.825rem; }
    .info-item:last-child { border-bottom:none; padding-bottom:0; }
    .info-item-icon { width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
    .info-item-icon.req { background:var(--danger-light); }
    .info-item-icon.req i { color:var(--danger); font-size:0.6rem; }
    .info-item-icon.opt { background:var(--primary-light); }
    .info-item-icon.opt i { color:var(--primary); font-size:0.6rem; }
    .info-item-icon.tip { background:var(--success-light); }
    .info-item-icon.tip i { color:var(--success); font-size:0.6rem; }
    .info-item-text { color:var(--text-secondary); line-height:1.5; }
    .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); padding:1.25rem; display:flex; align-items:center; gap:1rem; }
    .stat-card-icon { width:44px; height:44px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .stat-card-icon.purple { background:var(--primary-light); }
    .stat-card-icon.purple i { color:var(--primary); font-size:1.1rem; }
    .stat-card-value { font-size:1.35rem; font-weight:700; color:var(--text-primary); line-height:1; }
    .stat-card-label { font-size:0.775rem; color:var(--text-muted); margin-top:2px; }
    .toast-container-custom { position:fixed; top:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:0.5rem; pointer-events:none; }
    .toast-custom { display:flex; align-items:flex-start; gap:0.75rem; padding:1rem 1.25rem; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-lg); min-width:300px; max-width:400px; animation:toastIn 0.3s ease; position:relative; overflow:hidden; pointer-events:all; }
    .toast-custom::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; }
    .toast-custom.success::before { background:var(--success); }
    .toast-custom.error::before { background:var(--danger); }
    .toast-icon { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .toast-icon.success { background:var(--success-light); }
    .toast-icon.success i { color:var(--success); }
    .toast-icon.error { background:var(--danger-light); }
    .toast-icon.error i { color:var(--danger); }
    .toast-title { font-size:0.875rem; font-weight:600; color:var(--text-primary); }
    .toast-msg { font-size:0.8rem; color:var(--text-secondary); margin-top:2px; }
    @keyframes toastIn { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
    @keyframes toastOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(24px)} }
    @media(max-width:768px){ .form-card-body{padding:1.25rem;} .action-info{display:none;} }
</style>

<div class="toast-container-custom" id="toastContainer"></div>

<div class="page-wrapper">
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h1 class="page-title">नया जोड़ें</h1>
                <p class="page-subtitle">साधु / साध्वी जी की जानकारी दर्ज करें</p>
            </div>
        </div>
        <a href="/admin/sadhu-sadvi" class="btn-back">
            <i class="fas fa-arrow-left"></i> वापस जाएं
        </a>
    </div>

    <div class="row g-4">
        {{-- ── Full-width landscape card ── --}}
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <p class="form-card-title">नया साधु / साध्वी जोड़ें</p>
                        <p class="form-card-subtitle">सभी अनिवार्य क्षेत्र भरें</p>
                    </div>

                    {{-- Live Counter inside header (right side) --}}
                    <div class="ms-auto" id="thanaStatCard" style="display:none">
                        <div style="display:flex;align-items:center;gap:1.25rem;background:rgba(255,255,255,0.15);border-radius:10px;padding:0.5rem 1.25rem;backdrop-filter:blur(8px);">
                            <div style="text-align:center;">
                                <div style="font-size:1.25rem;font-weight:700;color:#fff;line-height:1;" id="thanaStatValue">0</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.75);margin-top:2px;">कुल सदस्य</div>
                            </div>
                            <div style="width:1px;height:32px;background:rgba(255,255,255,0.3);"></div>
                            <div style="text-align:center;">
                                <div style="font-size:1.25rem;font-weight:700;color:#fde68a;line-height:1;" id="extraStatValue">0</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.75);margin-top:2px;">अतिरिक्त नाम</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-card-body">
                    <form id="sadhuSadviForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf

                        {{-- Row 1: Name + Thana --}}
                        <div class="row g-4 mb-0">
                            <div class="col-md-8">
                                <div class="field-group mb-0">
                                    <label for="name" class="field-label">
                                        <span class="label-icon"><i class="fas fa-user"></i></span>
                                        नाम
                                        <span class="required-badge">अनिवार्य</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-field @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        placeholder="संत / सतिया जी का पूरा नाम दर्ज करें"
                                        required
                                        autocomplete="off"
                                    >
                                    @error('name')
                                        <div class="invalid-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group mb-0">
                                    <label for="thana" class="field-label">
                                        <span class="label-icon"><i class="fas fa-hashtag"></i></span>
                                        ठाणा संख्या
                                        <span class="required-badge">अनिवार्य</span>
                                    </label>
                                    <input
                                        type="number"
                                        class="form-field"
                                        id="thana"
                                        name="thana"
                                        min="1"
                                        placeholder="उदाहरण: 5"
                                        required
                                        autocomplete="off"
                                    >
                                    <div class="field-hint">
                                        <i class="fas fa-lightbulb"></i>
                                        ठाणा = 1 → कोई अतिरिक्त नाम नहीं
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Row 2: Address + Location --}}
                        <div class="row g-4 mt-0" style="margin-top:1.25rem!important;">
                            <div class="col-md-6">
                                <div class="field-group mb-0">
                                    <label for="address" class="field-label">
                                        <span class="label-icon"><i class="fas fa-map-marker-alt"></i></span>
                                        पता
                                        <span class="optional-badge">वैकल्पिक</span>
                                    </label>
                                    <textarea
                                        class="form-field @error('address') is-invalid @enderror"
                                        id="address"
                                        name="address"
                                        rows="3"
                                        placeholder="पूरा पता दर्ज करें (मोहल्ला, शहर, राज्य)"
                                    ></textarea>
                                    @error('address')
                                        <div class="invalid-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group mb-0">
                                    <label for="link" class="field-label">
                                        <span class="label-icon"><i class="fas fa-map-pin"></i></span>
                                        Location Link
                                        <span class="optional-badge">वैकल्पिक</span>
                                    </label>
                                    <input
                                        type="url"
                                        class="form-field @error('link') is-invalid @enderror"
                                        id="link"
                                        name="link"
                                        placeholder="https://maps.google.com/..."
                                        autocomplete="off"
                                    >
                                    @error('link')
                                        <div class="invalid-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                    <div class="field-hint">
                                        <i class="fas fa-info-circle"></i>
                                        Google Maps या किसी भी मानचित्र सेवा का लिंक
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Thana Names --}}
                        <div id="thana-names-container" style="margin-top:1.5rem;"></div>

                        {{-- Action Bar --}}
                        <div class="action-bar">
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-save"></i> जोड़ें
                            </button>
                            <a href="/admin/sadhu-sadvi" class="btn-cancel">
                                <i class="fas fa-times"></i> रद्द करें
                            </a>
                            <span class="action-info">
                                <i class="fas fa-lock"></i> डेटा सुरक्षित रूप से सहेजा जाएगा
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
const thanaInput = document.getElementById('thana');
const container  = document.getElementById('thana-names-container');
const statCard   = document.getElementById('thanaStatCard');
const statValue  = document.getElementById('thanaStatValue');
const extraValue = document.getElementById('extraStatValue');

thanaInput.addEventListener('input', function () {
    const thana = parseInt(this.value) || 0;
    container.innerHTML = '';

    if (thana >= 1) {
        statCard.style.display = 'flex';
        statValue.textContent  = thana;
        extraValue.textContent = Math.max(0, thana - 1);
    } else {
        statCard.style.display = 'none';
    }

    if (thana < 2) return;

    const extra = thana - 1;
    let html = `
    <div class="thana-panel">
        <div class="thana-panel-header">
            <div class="thana-panel-header-icon"><i class="fas fa-user-friends"></i></div>
            <span class="thana-panel-title">अतिरिक्त सदस्यों के नाम</span>
            <span class="thana-panel-count">${extra} नाम</span>
        </div>
        <div class="thana-panel-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.875rem;padding:1.25rem;">`;

    for (let i = 1; i <= extra; i++) {
        html += `
            <div class="thana-name-row">
                <div class="thana-index-badge">${i}</div>
                <input
                    type="text"
                    class="thana-name-input"
                    name="thana_names[]"
                    placeholder="साधु / साध्वी जी ${i} का नाम"
                    required
                    autocomplete="off"
                >
            </div>`;
    }

    html += `</div></div>`;
    container.innerHTML = html;
});

document.getElementById('sadhuSadviForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const name    = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const link    = document.getElementById('link').value.trim();
    const thana   = document.getElementById('thana').value;
    const btn     = document.getElementById('submitBtn');

    if (!name) {
        showToast('error', 'अनिवार्य क्षेत्र', 'कृपया नाम दर्ज करें');
        document.getElementById('name').focus();
        return;
    }
    if (!thana || parseInt(thana) < 1) {
        showToast('error', 'अनिवार्य क्षेत्र', 'कृपया ठाणा संख्या दर्ज करें');
        thanaInput.focus();
        return;
    }

    const thanaInputs = document.querySelectorAll('.thana-name-input');
    const thanaNames  = [];
    for (const inp of thanaInputs) {
        if (!inp.value.trim()) {
            showToast('error', 'अनिवार्य क्षेत्र', 'कृपया सभी ठाणा सदस्यों के नाम दर्ज करें');
            inp.focus();
            return;
        }
        thanaNames.push(inp.value.trim());
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> प्रसंस्करण...';

    try {
        const response = await fetch('/api/sadhu-sadvi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                name:        name,
                address:     address || null,
                link:        link    || null,
                thana:       parseInt(thana),
                thana_names: thanaNames.length > 0 ? thanaNames : null,
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast('success', 'सफलतापूर्वक जोड़ा गया!', 'साधु / साध्वी जी की जानकारी सहेज ली गई है।');
            setTimeout(() => { window.location.href = '/admin/sadhu-sadvi'; }, 1400);
        } else {
            showToast('error', 'त्रुटि', data.message || 'कुछ गलत हुआ, पुनः प्रयास करें');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> जोड़ें';
        }
    } catch (err) {
        showToast('error', 'नेटवर्क त्रुटि', err.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> जोड़ें';
    }
});

function showToast(type, title, msg) {
    const tc    = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-custom ${type}`;
    toast.innerHTML = `
        <div class="toast-icon ${type}">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
        </div>
        <div>
            <div class="toast-title">${title}</div>
            <div class="toast-msg">${msg}</div>
        </div>`;
    tc.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
</script>

@endsection
