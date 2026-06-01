?@extends('admin.layout')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #eef2ff;
        --success: #059669;
        --success-light: #ecfdf5;
        --danger: #dc2626;
        --danger-light: #fef2f2;
        --warning: #d97706;
        --warning-light: #fffbeb;
        --surface: #ffffff;
        --surface-alt: #f8fafc;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --radius: 14px;
        --radius-sm: 8px;
        --shadow-md: 0 4px 16px rgba(0,0,0,0.07),0 2px 6px rgba(0,0,0,0.04);
        --shadow-lg: 0 12px 40px rgba(79,70,229,0.1),0 4px 16px rgba(0,0,0,0.06);
        --transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }

    .page-wrapper { background:linear-gradient(135deg,#f0f4ff 0%,#fafbff 60%,#f0fdf4 100%); min-height:100vh; padding:2rem 0; }

    /* Header */
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .page-header-left { display:flex; align-items:center; gap:1rem; }
    .page-header-icon { width:52px; height:52px; background:linear-gradient(135deg,var(--primary),#7c3aed); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(79,70,229,0.35); flex-shrink:0; }
    .page-header-icon i { color:#fff; font-size:1.25rem; }
    .page-title { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0; letter-spacing:-0.3px; }
    .page-subtitle { font-size:0.875rem; color:var(--text-secondary); margin:0; }
    .btn-add { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.5rem; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border:none; border-radius:var(--radius-sm); font-size:0.9375rem; font-weight:600; text-decoration:none; transition:var(--transition); box-shadow:0 4px 14px rgba(79,70,229,0.35); cursor:pointer; }
    .btn-add:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,0.45); color:#fff; text-decoration:none; }

    /* Stat Cards */
    .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1.25rem; margin-bottom:1.75rem; }
    .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; transition:var(--transition); }
    .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-lg); }
    .stat-icon { width:46px; height:46px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .stat-icon.purple { background:var(--primary-light); }
    .stat-icon.purple i { color:var(--primary); font-size:1.1rem; }
    .stat-icon.green { background:var(--success-light); }
    .stat-icon.green i { color:var(--success); font-size:1.1rem; }
    .stat-icon.sky { background:#e0f2fe; }
    .stat-icon.sky i { color:#0284c7; font-size:1.1rem; }
    .stat-value { font-size:1.6rem; font-weight:700; color:var(--text-primary); line-height:1; }
    .stat-label { font-size:0.775rem; color:var(--text-muted); margin-top:3px; font-weight:500; }

    /* Table Card */
    .table-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-lg); overflow:hidden; }
    .table-card-header { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; background:linear-gradient(135deg,var(--primary) 0%,#7c3aed 100%); flex-wrap:wrap; gap:0.75rem; }
    .table-card-header-left { display:flex; align-items:center; gap:0.75rem; }
    .table-header-icon { width:34px;height:34px;background:rgba(255,255,255,0.2);border-radius:6px;display:flex;align-items:center;justify-content:center; }
    .table-header-icon i { color:#fff; font-size:0.875rem; }
    .table-title { color:#fff; font-size:0.875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; }
    .search-box { display:flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.25); border-radius:var(--radius-sm); padding:0.5rem 0.875rem; backdrop-filter:blur(8px); transition:var(--transition); }
    .search-box:focus-within { background:rgba(255,255,255,0.25); border-color:rgba(255,255,255,0.5); }
    .search-box i { color:rgba(255,255,255,0.7); font-size:0.8rem; }
    .search-box input { background:transparent; border:none; outline:none; color:#fff; font-size:0.875rem; width:200px; }
    .search-box input::placeholder { color:rgba(255,255,255,0.6); }

    /* Table */
    .data-table { width:100%; border-collapse:collapse; }
    .data-table thead th { padding:0.875rem 1.25rem; font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; background:var(--surface-alt); border-bottom:1px solid var(--border); white-space:nowrap; }
    .data-table tbody td { padding:1rem 1.25rem; font-size:0.9rem; color:var(--text-primary); border-bottom:1px solid var(--border); vertical-align:middle; }
    .data-table tbody tr:last-child td { border-bottom:none; }
    .data-table tbody tr { transition:var(--transition); cursor:pointer; }
    .data-table tbody tr:hover td { background:#f5f7ff; }

    /* Row cells */
    .name-cell-main { font-weight:600; color:var(--text-primary); }
    .name-cell-sub { font-size:0.75rem; color:var(--text-muted); margin-top:2px; }
    .thana-pill { display:inline-flex; align-items:center; gap:0.3rem; padding:3px 10px; background:var(--primary-light); color:var(--primary); border-radius:20px; font-size:0.75rem; font-weight:700; }
    .members-pill { display:inline-flex; align-items:center; gap:0.3rem; padding:3px 10px; background:var(--success-light); color:var(--success); border-radius:20px; font-size:0.75rem; font-weight:700; }
    .addr-cell { font-size:0.85rem; color:var(--text-secondary); max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .link-btn-sm { display:inline-flex; align-items:center; gap:0.3rem; padding:3px 10px; background:var(--success-light); color:var(--success); border:1px solid rgba(5,150,105,0.2); border-radius:6px; font-size:0.75rem; font-weight:600; text-decoration:none; transition:var(--transition); }
    .link-btn-sm:hover { background:var(--success); color:#fff; text-decoration:none; }
    .no-data-badge { display:inline-flex; align-items:center; padding:2px 8px; background:var(--surface-alt); color:var(--text-muted); border-radius:6px; font-size:0.75rem; }

    /* Action buttons */
    .action-btns { display:flex; align-items:center; gap:0.375rem; }
    .action-btn { width:32px; height:32px; border-radius:6px; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:var(--transition); flex-shrink:0; }
    .action-btn.view   { background:#e0f2fe; color:#0284c7; }
    .action-btn.edit   { background:var(--warning-light); color:var(--warning); }
    .action-btn.delete { background:var(--danger-light); color:var(--danger); }
    .action-btn:hover { opacity:0.8; transform:scale(1.1); }
    .action-btn i { font-size:0.75rem; }

    /* Empty / Loading */
    .table-empty { padding:4rem; text-align:center; color:var(--text-muted); }
    .table-empty i { font-size:3rem; margin-bottom:1rem; display:block; opacity:0.35; }
    .table-empty-text { font-size:0.9rem; }
    .skeleton { background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; border-radius:6px; height:18px; }
    @keyframes shimmer { from{background-position:200% 0} to{background-position:-200% 0} }

    /* Modals */
    .modal-header-custom { padding:1.25rem 1.5rem; background:linear-gradient(135deg,var(--primary),#7c3aed); }
    .modal-header-custom.danger { background:linear-gradient(135deg,#dc2626,#b91c1c); }
    .modal-header-custom .modal-title { color:#fff; font-weight:600; font-size:1rem; display:flex; align-items:center; gap:0.5rem; }
    .modal-header-custom .btn-close { filter:invert(1) brightness(2); }
    .mfield-label { font-size:0.78rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.4rem; }
    .mfield-input { width:100%; padding:0.7rem 0.9rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-size:0.9375rem; color:var(--text-primary); outline:none; font-family:inherit; transition:var(--transition); box-sizing:border-box; }
    .mfield-input:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,0.1); }
    textarea.mfield-input { resize:vertical; min-height:80px; }
    .btn-modal-save { display:inline-flex; align-items:center; gap:0.5rem; padding:0.65rem 1.5rem; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border:none; border-radius:var(--radius-sm); font-weight:600; font-size:0.875rem; cursor:pointer; transition:var(--transition); }
    .btn-modal-save:hover { opacity:0.9; transform:translateY(-1px); }
    .btn-modal-danger { display:inline-flex; align-items:center; gap:0.5rem; padding:0.65rem 1.5rem; background:var(--danger); color:#fff; border:none; border-radius:var(--radius-sm); font-weight:600; font-size:0.875rem; cursor:pointer; transition:var(--transition); }
    .btn-modal-danger:hover { background:#b91c1c; }
    .btn-modal-cancel { display:inline-flex; align-items:center; gap:0.5rem; padding:0.65rem 1.25rem; background:var(--surface); color:var(--text-secondary); border:1.5px solid var(--border); border-radius:var(--radius-sm); font-weight:500; font-size:0.875rem; cursor:pointer; transition:var(--transition); }
    .btn-modal-cancel:hover { background:var(--surface-alt); }

    /* Toast */
    .toast-container-custom { position:fixed; top:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:0.5rem; pointer-events:none; }
    .toast-c { display:flex; align-items:flex-start; gap:0.75rem; padding:1rem 1.25rem; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-lg); min-width:300px; max-width:400px; animation:toastIn 0.3s ease; position:relative; overflow:hidden; pointer-events:all; }
    .toast-c::before { content:''; position:absolute; left:0;top:0;bottom:0;width:4px; }
    .toast-c.success::before { background:var(--success); }
    .toast-c.error::before { background:var(--danger); }
    .t-icon { width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .t-icon.success { background:var(--success-light); } .t-icon.success i { color:var(--success); }
    .t-icon.error { background:var(--danger-light); } .t-icon.error i { color:var(--danger); }
    .t-title { font-size:0.875rem;font-weight:600;color:var(--text-primary); }
    .t-msg { font-size:0.78rem;color:var(--text-secondary);margin-top:2px; }
    @keyframes toastIn { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
    @keyframes toastOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(24px)} }

    .member-chips-cell { display:flex; flex-wrap:wrap; gap:3px; max-width:300px; }
    .member-chip { display:inline-flex; align-items:center; padding:2px 9px; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe; border-radius:20px; font-size:0.72rem; font-weight:600; white-space:nowrap; }
    @media(max-width:768px){ .search-box input{width:140px;} .stats-row{grid-template-columns:repeat(2,1fr);} }
</style>

<div class="toast-container-custom" id="toastContainer"></div>

<div class="page-wrapper">
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h1 class="page-title">संत / सतिया सूची</h1>
                <p class="page-subtitle">सभी साधु / साध्वी जी की जानकारी प्रबंधित करें</p>
            </div>
        </div>
        <a href="/admin/sadhu-sadvi/create" class="btn-add">
            <i class="bi bi-plus-lg"></i> नया जोड़ें
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value" id="statTotal">—</div>
                <div class="stat-label">कुल रिकॉर्ड</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
            <div>
                <div class="stat-value" id="statTotalMembers">—</div>
                <div class="stat-label">कुल ठाणा सदस्य</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon sky"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <div class="stat-value" id="statWithAddr">—</div>
                <div class="stat-label">पता उपलब्ध</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-header-left">
                <div class="table-header-icon"><i class="bi bi-list-ul"></i></div>
                <span class="table-title">संत / सतिया सूची</span>
            </div>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="नाम या पता से खोजें...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="sadhuSadviTable">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>नाम</th>
                        <th style="width:90px;">थाना</th>
                        <th>ठाणा संत नाम</th>
                        <th>पता</th>
                        <th style="width:100px;">Link</th>
                        <th style="width:110px;">क्रिया</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="7" style="padding:3rem;">
                            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                                <div class="skeleton" style="width:100%;"></div>
                                <div class="skeleton" style="width:85%;"></div>
                                <div class="skeleton" style="width:92%;"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header-custom">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> संपादित करें</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" id="editId">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mfield-label">नाम <span style="color:var(--danger)">*</span></div>
                            <input type="text" class="mfield-input" id="editName" required placeholder="साधु / साध्वी जी का नाम">
                        </div>
                        <div class="col-md-4">
                            <div class="mfield-label">ठाणा संख्या</div>
                            <input type="number" class="mfield-input" id="editThana" min="1" placeholder="उदाहरण: 5">
                        </div>
                        <div class="col-md-6">
                            <div class="mfield-label">पता</div>
                            <textarea class="mfield-input" id="editAddress" rows="3" placeholder="पूरा पता..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="mfield-label">Location Link</div>
                            <input type="url" class="mfield-input" id="editLink" placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:1rem 1.5rem;gap:0.5rem;">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> रद्द करें</button>
                    <button type="submit" class="btn-modal-save" id="editSubmitBtn"><i class="bi bi-check2"></i> अपडेट करें</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header-custom danger">
                <h5 class="modal-title"><i class="bi bi-trash3-fill"></i> हटाने की पुष्टि</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;gap:1rem;align-items:flex-start;">
                    <div style="width:44px;height:44px;background:var(--danger-light);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:var(--danger);"></i>
                    </div>
                    <div>
                        <p style="font-weight:600;color:var(--text-primary);margin-bottom:0.25rem;">क्या आप वाकई हटाना चाहते हैं?</p>
                        <p style="color:var(--text-secondary);font-size:0.875rem;margin-bottom:0.5rem;"><strong id="deleteItemName"></strong> को हटाया जाएगा।</p>
                        <p style="color:var(--danger);font-size:0.8rem;margin:0;">⚠ यह क्रिया वापस नहीं की जा सकती।</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border);padding:1rem 1.5rem;gap:0.5rem;">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">नहीं, रखें</button>
                <button type="button" class="btn-modal-danger" id="confirmDeleteBtn"><i class="bi bi-trash3-fill"></i> हटाएं</button>
            </div>
        </div>
    </div>
</div>

<script>
let allData = [];
let selectedDeleteId = null;
let editModal, deleteModal;

document.addEventListener('DOMContentLoaded', function () {
    editModal   = new bootstrap.Modal(document.getElementById('editModal'));
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    loadData();
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderTable(allData.filter(d =>
            d.name.toLowerCase().includes(q) ||
            (d.address && d.address.toLowerCase().includes(q)) ||
            (d.thana_sants && d.thana_sants.some(s => s.sant_name.toLowerCase().includes(q)))
        ));
    });
    document.getElementById('editForm').addEventListener('submit', handleEditSubmit);
    document.getElementById('confirmDeleteBtn').addEventListener('click', handleDelete);
});

function loadData() {
    fetch('/api/sadhu-sadvi')
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                allData = res.data;
                updateStats(allData);
                renderTable(allData);
            } else {
                showToast('error', 'त्रुटि', res.message);
                renderEmpty();
            }
        })
        .catch(err => { showToast('error', 'नेटवर्क त्रुटि', err.message); renderEmpty(); });
}

function updateStats(data) {
    document.getElementById('statTotal').textContent       = data.length;
    document.getElementById('statTotalMembers').textContent = data.reduce((s, d) => s + (d.thana_sants_count || 0), 0);
    document.getElementById('statWithAddr').textContent    = data.filter(d => d.address).length;
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (!data.length) { renderEmpty(); return; }

    tbody.innerHTML = data.map((item, i) => {
        const santNames = (item.thana_sants || []).map(s =>
            `<span class="member-chip">${escHtml(s.sant_name)}</span>`
        ).join('');
        return `
        <tr onclick="viewDetails(${item.id})">
            <td style="font-weight:700;color:var(--text-muted);">${i + 1}</td>
            <td>
                <div class="name-cell-main">${escHtml(item.name)}</div>
                <div class="name-cell-sub">ID: ${item.id}</div>
            </td>
            <td><span class="thana-pill"><i class="bi bi-hash" style="font-size:0.65rem;"></i> ${item.thana || '�'}</span></td>
            <td><div class="member-chips-cell">${santNames || '<span class="no-data-badge">�</span>'}</div></td>
            <td>
                ${item.address
                    ? `<div class="addr-cell" title="${escHtml(item.address)}">${escHtml(item.address.substring(0, 50))}${item.address.length > 50 ? '…' : ''}</div>`
                    : '<span class="no-data-badge">—</span>'}
            </td>
            <td onclick="event.stopPropagation()">
                ${item.link
                    ? `<a href="${escHtml(item.link)}" target="_blank" rel="noopener" class="link-btn-sm"><i class="bi bi-box-arrow-up-right"></i> खुलें</a>`
                    : '<span class="no-data-badge">—</span>'}
            </td>
            <td onclick="event.stopPropagation()">
                <div class="action-btns">
                    <button class="action-btn view" onclick="viewDetails(${item.id})" title="विवरण देखें"><i class="bi bi-eye-fill"></i></button>
                    <button class="action-btn edit" onclick="openEditModal(${item.id})" title="संपादित करें"><i class="bi bi-pencil-square"></i></button>
                    <button class="action-btn delete" onclick="openDeleteModal(${item.id}, '${escJs(item.name)}')" title="हटाएं"><i class="bi bi-trash3-fill"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderEmpty() {
    document.getElementById('tableBody').innerHTML = `
        <tr><td colspan="7">
            <div class="table-empty">
                <i class="bi bi-inbox"></i>
                <div class="table-empty-text">कोई डेटा नहीं मिला</div>
            </div>
        </td></tr>`;
}

function viewDetails(id) {
    window.location.href = `/admin/sadhu-sadvi/show?id=${id}`;
}

function openEditModal(id) {
    const item = allData.find(d => d.id === id);
    if (!item) return;
    document.getElementById('editId').value      = item.id;
    document.getElementById('editName').value    = item.name || '';
    document.getElementById('editThana').value   = item.thana || '';
    document.getElementById('editAddress').value = item.address || '';
    document.getElementById('editLink').value    = item.link || '';
    editModal.show();
}

async function handleEditSubmit(e) {
    e.preventDefault();
    const id  = document.getElementById('editId').value;
    const btn = document.getElementById('editSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> अपडेट...';
    try {
        const res  = await fetch(`/api/sadhu-sadvi/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body: JSON.stringify({
                name:    document.getElementById('editName').value,
                thana:   parseInt(document.getElementById('editThana').value) || null,
                address: document.getElementById('editAddress').value || null,
                link:    document.getElementById('editLink').value || null,
            })
        });
        const data = await res.json();
        if (data.success) {
            editModal.hide();
            showToast('success', 'अपडेट हो गया!', 'जानकारी सफलतापूर्वक अपडेट की गई।');
            loadData();
        } else {
            showToast('error', 'त्रुटि', data.message || 'अपडेट नहीं हो सका');
        }
    } catch (err) { showToast('error', 'नेटवर्क त्रुटि', err.message); }
    finally { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2"></i> अपडेट करें'; }
}

function openDeleteModal(id, name) {
    selectedDeleteId = id;
    document.getElementById('deleteItemName').textContent = name;
    deleteModal.show();
}

async function handleDelete() {
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> हटा रहे हैं...';
    try {
        const res  = await fetch(`/api/sadhu-sadvi/${selectedDeleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();
        if (data.success) {
            deleteModal.hide();
            showToast('success', 'हटाया गया!', 'रिकॉर्ड सफलतापूर्वक हटाया गया।');
            loadData();
        } else {
            showToast('error', 'त्रुटि', data.message);
        }
    } catch (err) { showToast('error', 'नेटवर्क त्रुटि', err.message); }
    finally { btn.disabled = false; btn.innerHTML = '<i class="bi bi-trash3-fill"></i> हटाएं'; }
}

function showToast(type, title, msg) {
    const tc    = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-c ${type}`;
    toast.innerHTML = `
        <div class="t-icon ${type}"><i class="bi ${type==='success'?'bi-check-circle-fill':'bi-exclamation-triangle-fill'}"></i></div>
        <div><div class="t-title">${title}</div><div class="t-msg">${msg}</div></div>`;
    tc.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function csrfToken() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
}

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function escJs(s) {
    if (!s) return '';
    return String(s).replace(/'/g,"\\'").replace(/\n/g,'\\n');
}
</script>

@endsection
