@extends('admin.layout')

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

    .page-wrapper { background: linear-gradient(135deg,#f0f4ff 0%,#fafbff 60%,#f0fdf4 100%); min-height:100vh; padding:2rem 0; }

    /* ── Header ── */
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .page-header-left { display:flex; align-items:center; gap:1rem; }
    .page-header-icon { width:52px; height:52px; background:linear-gradient(135deg,var(--primary),#7c3aed); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(79,70,229,0.35); flex-shrink:0; }
    .page-header-icon i { color:#fff; font-size:1.25rem; }
    .page-title { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0; letter-spacing:-0.3px; }
    .page-subtitle { font-size:0.875rem; color:var(--text-secondary); margin:0; }
    .btn-back { display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius-sm); color:var(--text-secondary); font-weight:500; font-size:0.875rem; text-decoration:none; transition:var(--transition); box-shadow:var(--shadow-md); }
    .btn-back:hover { background:var(--primary-light); border-color:var(--primary); color:var(--primary); text-decoration:none; }

    /* ── Hero Card ── */
    .hero-card { background:var(--surface); border-radius:var(--radius); border:1px solid var(--border); box-shadow:var(--shadow-lg); overflow:hidden; margin-bottom:1.5rem; }
    .hero-banner { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 50%,#0ea5e9 100%); padding:2rem 2rem 3.5rem; position:relative; overflow:hidden; }
    .hero-banner::before { content:''; position:absolute; top:-40px; right:-40px; width:200px; height:200px; background:rgba(255,255,255,0.06); border-radius:50%; }
    .hero-banner::after { content:''; position:absolute; bottom:-60px; left:-30px; width:160px; height:160px; background:rgba(255,255,255,0.04); border-radius:50%; }
    .hero-avatar { width:72px; height:72px; background:rgba(255,255,255,0.2); border:3px solid rgba(255,255,255,0.4); border-radius:50%; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(8px); margin-bottom:1rem; }
    .hero-avatar i { color:#fff; font-size:1.75rem; }
    .hero-name { font-size:1.75rem; font-weight:700; color:#fff; margin:0 0 0.25rem; letter-spacing:-0.3px; }
    .hero-id { display:inline-flex; align-items:center; gap:0.375rem; background:rgba(255,255,255,0.18); color:rgba(255,255,255,0.9); padding:3px 12px; border-radius:20px; font-size:0.78rem; font-weight:600; backdrop-filter:blur(8px); }
    .hero-actions { position:absolute; top:1.5rem; right:1.5rem; display:flex; gap:0.5rem; z-index:1; }
    .hero-action-btn { display:inline-flex; align-items:center; gap:0.375rem; padding:0.5rem 1rem; border-radius:var(--radius-sm); font-size:0.8rem; font-weight:600; border:none; cursor:pointer; transition:var(--transition); }
    .hero-action-btn.edit { background:rgba(255,255,255,0.2); color:#fff; backdrop-filter:blur(8px); border:1.5px solid rgba(255,255,255,0.3); }
    .hero-action-btn.edit:hover { background:rgba(255,255,255,0.35); }
    .hero-action-btn.delete { background:rgba(220,38,38,0.25); color:#fecaca; backdrop-filter:blur(8px); border:1.5px solid rgba(220,38,38,0.4); }
    .hero-action-btn.delete:hover { background:rgba(220,38,38,0.45); color:#fff; }

    /* ── Stats Strip ── */
    .stats-strip { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:0; border-top:1px solid var(--border); }
    .stat-item { padding:1.25rem 1.5rem; text-align:center; border-right:1px solid var(--border); }
    .stat-item:last-child { border-right:none; }
    .stat-item-value { font-size:1.5rem; font-weight:700; color:var(--text-primary); line-height:1; }
    .stat-item-label { font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; margin-top:4px; font-weight:600; }

    /* ── Info Grid ── */
    .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1.5rem; margin-bottom:1.5rem; }
    .info-section { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden; }
    .info-section-header { display:flex; align-items:center; gap:0.625rem; padding:1rem 1.5rem; border-bottom:1px solid var(--border); background:var(--surface-alt); }
    .info-section-header-icon { width:30px; height:30px; border-radius:6px; display:flex; align-items:center; justify-content:center; }
    .info-section-header-icon.purple { background:var(--primary-light); }
    .info-section-header-icon.purple i { color:var(--primary); font-size:0.8rem; }
    .info-section-header-icon.green { background:var(--success-light); }
    .info-section-header-icon.green i { color:var(--success); font-size:0.8rem; }
    .info-section-header-icon.blue { background:#e0f2fe; }
    .info-section-header-icon.blue i { color:#0284c7; font-size:0.8rem; }
    .info-section-title { font-size:0.8rem; font-weight:700; color:var(--text-primary); text-transform:uppercase; letter-spacing:0.6px; }
    .info-section-body { padding:1.5rem; }
    .info-row { display:flex; align-items:flex-start; gap:1rem; padding:0.875rem 0; border-bottom:1px solid var(--border); }
    .info-row:last-child { border-bottom:none; padding-bottom:0; }
    .info-row-icon { width:36px; height:36px; border-radius:8px; background:var(--surface-alt); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
    .info-row-icon i { color:var(--text-secondary); font-size:0.85rem; }
    .info-row-label { font-size:0.72rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px; }
    .info-row-value { font-size:0.9375rem; color:var(--text-primary); font-weight:500; line-height:1.5; word-break:break-word; }
    .info-row-value.muted { color:var(--text-muted); font-style:italic; font-size:0.875rem; }
    .link-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.4rem 0.875rem; background:var(--success-light); color:var(--success); border:1.5px solid rgba(5,150,105,0.2); border-radius:6px; font-size:0.8rem; font-weight:600; text-decoration:none; transition:var(--transition); }
    .link-btn:hover { background:var(--success); color:#fff; text-decoration:none; }

    /* ── Members Table ── */
    .members-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden; margin-bottom:1.5rem; }
    .members-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#0ea5e9,#0284c7); }
    .members-header-left { display:flex; align-items:center; gap:0.625rem; }
    .members-header-icon { width:30px; height:30px; background:rgba(255,255,255,0.2); border-radius:6px; display:flex; align-items:center; justify-content:center; }
    .members-header-icon i { color:#fff; font-size:0.8rem; }
    .members-header-title { font-size:0.8rem; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.6px; }
    .members-badge { background:rgba(255,255,255,0.25); color:#fff; padding:2px 10px; border-radius:20px; font-size:0.75rem; font-weight:700; }
    .members-table { width:100%; border-collapse:collapse; }
    .members-table th { padding:0.75rem 1.5rem; font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; background:var(--surface-alt); border-bottom:1px solid var(--border); }
    .members-table td { padding:1rem 1.5rem; font-size:0.9rem; color:var(--text-primary); border-bottom:1px solid var(--border); }
    .members-table tr:last-child td { border-bottom:none; }
    .members-table tr:hover td { background:#fafbff; }
    .member-index { width:36px; height:36px; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700; }
    .main-member-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:2px 8px; background:linear-gradient(135deg,var(--primary),#7c3aed); color:#fff; border-radius:20px; font-size:0.68rem; font-weight:700; }
    .empty-members { padding:3rem; text-align:center; color:var(--text-muted); }
    .empty-members i { font-size:2.5rem; margin-bottom:0.75rem; display:block; opacity:0.4; }
    .empty-members-text { font-size:0.875rem; }

    /* ── Metadata ── */
    .meta-row { display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:var(--text-muted); }
    .meta-row i { font-size:0.72rem; }

    /* ── Status Chip ── */
    .status-chip { display:inline-flex; align-items:center; gap:0.375rem; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; }
    .status-chip.active { background:var(--success-light); color:var(--success); }
    .status-chip .dot { width:6px; height:6px; border-radius:50%; background:currentColor; }

    /* ── Modals ── */
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

    /* ── Toast ── */
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

    /* ── Loading ── */
    .skeleton { background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; border-radius:8px; }
    @keyframes shimmer { from{background-position:200% 0} to{background-position:-200% 0} }

    @media(max-width:768px){ .hero-actions{position:static;margin-top:1rem;} .info-grid{grid-template-columns:1fr;} }
</style>

<div class="toast-container-custom" id="toastContainer"></div>

<div class="page-wrapper">
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <h1 class="page-title" id="headerTitle">विवरण</h1>
                <p class="page-subtitle">साधु / साध्वी जी की संपूर्ण जानकारी</p>
            </div>
        </div>
        <a href="/admin/sadhu-sadvi" class="btn-back">
            <i class="fas fa-arrow-left"></i> वापस जाएं
        </a>
    </div>

    {{-- Loading Skeleton --}}
    <div id="loadingState">
        <div style="background:#fff;border-radius:14px;overflow:hidden;margin-bottom:1.5rem;border:1px solid #e2e8f0;">
            <div style="padding:2rem;background:linear-gradient(135deg,#c7d2fe,#a5b4fc);height:160px;"></div>
            <div style="padding:1.5rem;display:flex;gap:2rem;">
                <div class="skeleton" style="height:24px;flex:2;"></div>
                <div class="skeleton" style="height:24px;flex:1;"></div>
                <div class="skeleton" style="height:24px;flex:1;"></div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div id="mainContent" style="display:none;">

        {{-- Hero Card --}}
        <div class="hero-card">
            <div class="hero-banner">
                <div class="hero-actions">
                    <button class="hero-action-btn edit" id="editBtn">
                        <i class="fas fa-edit"></i> संपादित करें
                    </button>
                    <button class="hero-action-btn delete" id="deleteBtn">
                        <i class="fas fa-trash"></i> हटाएं
                    </button>
                </div>
                <div class="hero-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h1 class="hero-name" id="profileName">—</h1>
                <div class="hero-id" id="profileId">ID: —</div>
            </div>
            <div class="stats-strip" id="statsStrip">
                <div class="stat-item">
                    <div class="stat-item-value" id="statThana">—</div>
                    <div class="stat-item-label">ठाणा संख्या</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item-value" id="statMembers">—</div>
                    <div class="stat-item-label">कुल सदस्य</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item-value">
                        <span class="status-chip active"><span class="dot"></span>सक्रिय</span>
                    </div>
                    <div class="stat-item-label">स्थिति</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item-value" id="statCreated" style="font-size:0.9rem;">—</div>
                    <div class="stat-item-label">पंजीकृत तिथि</div>
                </div>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">

            {{-- Basic Info --}}
            <div class="info-section">
                <div class="info-section-header">
                    <div class="info-section-header-icon purple"><i class="fas fa-user"></i></div>
                    <span class="info-section-title">मूल जानकारी</span>
                </div>
                <div class="info-section-body">
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-signature"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">पूरा नाम</div>
                            <div class="info-row-value" id="detailName">—</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">पता</div>
                            <div class="info-row-value" id="detailAddress">—</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-map-pin"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">Location Link</div>
                            <div class="info-row-value" id="detailLink">—</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Record Info --}}
            <div class="info-section">
                <div class="info-section-header">
                    <div class="info-section-header-icon green"><i class="fas fa-database"></i></div>
                    <span class="info-section-title">रिकॉर्ड जानकारी</span>
                </div>
                <div class="info-section-body">
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-hashtag"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">रिकॉर्ड ID</div>
                            <div class="info-row-value" id="infoId">—</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-users"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">ठाणा संख्या</div>
                            <div class="info-row-value" id="infoThana">—</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-calendar-plus"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">बनाया गया</div>
                            <div class="info-row-value" id="infoCreated">—</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-clock"></i></div>
                        <div style="flex:1;">
                            <div class="info-row-label">अंतिम अपडेट</div>
                            <div class="info-row-value" id="infoUpdated">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- All Members Combined Table --}}
        <div class="members-card">
            <div class="members-header">
                <div class="members-header-left">
                    <div class="members-header-icon"><i class="fas fa-users"></i></div>
                    <span class="members-header-title">ठाणा सदस्य सूची</span>
                </div>
                <span class="members-badge" id="membersBadge">0 सदस्य</span>
            </div>
            <div id="membersTableContainer">
                <div class="empty-members">
                    <i class="fas fa-user-slash"></i>
                    <div class="empty-members-text">कोई अतिरिक्त सदस्य नहीं</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Error State --}}
    <div id="errorState" style="display:none;">
        <div style="background:#fff;border-radius:var(--radius);padding:3rem;text-align:center;border:1px solid var(--border);box-shadow:var(--shadow-md);">
            <div style="width:64px;height:64px;background:var(--danger-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-exclamation-triangle" style="color:var(--danger);font-size:1.5rem;"></i>
            </div>
            <h4 style="color:var(--text-primary);margin-bottom:0.5rem;">डेटा लोड नहीं हो सका</h4>
            <p id="errorMessage" style="color:var(--text-secondary);font-size:0.875rem;margin-bottom:1.5rem;"></p>
            <a href="/admin/sadhu-sadvi" class="btn-back">
                <i class="fas fa-arrow-left"></i> वापस जाएं
            </a>
        </div>
    </div>

</div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-edit"></i> संपादित करें</h5>
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
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal"><i class="fas fa-times"></i> रद्द करें</button>
                    <button type="submit" class="btn-modal-save"><i class="fas fa-save"></i> अपडेट करें</button>
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
                <h5 class="modal-title"><i class="fas fa-trash"></i> हटाने की पुष्टि</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;gap:1rem;align-items:flex-start;">
                    <div style="width:44px;height:44px;background:var(--danger-light);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-exclamation-triangle" style="color:var(--danger);"></i>
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
                <button type="button" class="btn-modal-danger" id="confirmDeleteBtn"><i class="fas fa-trash"></i> हटाएं</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentData = null;
const urlParams = new URLSearchParams(window.location.search);
const itemId    = urlParams.get('id');

document.addEventListener('DOMContentLoaded', function () {
    if (!itemId) { showError('ID पैरामीटर नहीं मिला'); return; }
    loadDetails();
    setupListeners();
});

function loadDetails() {
    fetch(`/api/sadhu-sadvi/${itemId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                currentData = data.data;
                renderDetails();
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('mainContent').style.display  = 'block';
            } else {
                showError(data.message);
            }
        })
        .catch(err => showError('नेटवर्क त्रुटि: ' + err.message));
}

function renderDetails() {
    const d       = currentData;
    const members = d.thana_sants || [];
    const total   = members.length + 1; // main sant + extras

    // Header
    document.getElementById('headerTitle').textContent = d.name || 'विवरण';

    // Hero
    document.getElementById('profileName').textContent = d.name || '—';
    document.getElementById('profileId').textContent   = `ID: ${d.id}`;

    // Stats
    document.getElementById('statThana').textContent   = d.thana || '—';
    document.getElementById('statMembers').textContent  = total;
    document.getElementById('statCreated').textContent  = shortDate(d.created_at);

    // Basic Info
    document.getElementById('detailName').textContent = d.name || '—';

    const addrEl = document.getElementById('detailAddress');
    addrEl.innerHTML = d.address
        ? d.address.replace(/\n/g, '<br>')
        : '<span class="muted">पता उपलब्ध नहीं</span>';
    addrEl.className = d.address ? 'info-row-value' : 'info-row-value muted';

    const linkEl = document.getElementById('detailLink');
    linkEl.innerHTML = d.link
        ? `<a href="${escHtml(d.link)}" target="_blank" rel="noopener" class="link-btn"><i class="fas fa-external-link-alt"></i> खुलें</a>`
        : '<span class="info-row-value muted">लिंक उपलब्ध नहीं</span>';

    // Record Info
    document.getElementById('infoId').textContent      = `#${d.id}`;
    document.getElementById('infoThana').textContent   = d.thana || '—';
    document.getElementById('infoCreated').textContent = fullDate(d.created_at);
    document.getElementById('infoUpdated').textContent = fullDate(d.updated_at);

    // Members Table
    const badge = document.getElementById('membersBadge');
    badge.textContent = `${total} सदस्य`;

    const container = document.getElementById('membersTableContainer');

    // Build combined rows: main sant first, then thana_sants
    let rows = `
    <table class="members-table">
        <thead>
            <tr>
                <th style="width:60px;">#</th>
                <th>सदस्य का नाम</th>
                <th style="width:160px;">प्रकार</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><div class="member-index" style="background:linear-gradient(135deg,#059669,#047857);">M</div></td>
                <td style="font-weight:600;color:var(--text-primary);">${escHtml(d.name)}</td>
                <td><span class="main-member-badge"><i class="fas fa-star"></i> मुख्य</span></td>
            </tr>`;

    if (members.length > 0) {
        members.forEach((m, i) => {
            rows += `
            <tr>
                <td><div class="member-index">${i + 1}</div></td>
                <td>${escHtml(m.sant_name)}</td>
                <td><span style="font-size:0.78rem;color:var(--text-muted);">ठाणा सदस्य</span></td>
            </tr>`;
        });
    }

    rows += `</tbody></table>`;
    container.innerHTML = rows;
}

function setupListeners() {
    document.getElementById('editBtn').addEventListener('click', openEditModal);
    document.getElementById('deleteBtn').addEventListener('click', openDeleteModal);
    document.getElementById('editForm').addEventListener('submit', handleEditSubmit);
    document.getElementById('confirmDeleteBtn').addEventListener('click', handleDelete);
}

function openEditModal() {
    document.getElementById('editId').value      = currentData.id;
    document.getElementById('editName').value    = currentData.name || '';
    document.getElementById('editThana').value   = currentData.thana || '';
    document.getElementById('editAddress').value = currentData.address || '';
    document.getElementById('editLink').value    = currentData.link || '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

async function handleEditSubmit(e) {
    e.preventDefault();
    const id      = document.getElementById('editId').value;
    const btn     = e.target.querySelector('[type="submit"]');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> अपडेट हो रहा है...';

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
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            showToast('success', 'अपडेट हो गया!', 'जानकारी सफलतापूर्वक अपडेट की गई।');
            setTimeout(loadDetails, 400);
        } else {
            showToast('error', 'त्रुटि', data.message || 'अपडेट नहीं हो सका');
        }
    } catch (err) { showToast('error', 'नेटवर्क त्रुटि', err.message); }
    finally { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> अपडेट करें'; }
}

function openDeleteModal() {
    document.getElementById('deleteItemName').textContent = currentData.name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

async function handleDelete() {
    const btn     = document.getElementById('confirmDeleteBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> हटा रहे हैं...';
    try {
        const res  = await fetch(`/api/sadhu-sadvi/${currentData.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken() }
        });
        const data = await res.json();
        if (data.success) {
            showToast('success', 'हटाया गया!', 'रिकॉर्ड सफलतापूर्वक हटाया गया।');
            setTimeout(() => { window.location.href = '/admin/sadhu-sadvi'; }, 1200);
        } else {
            showToast('error', 'त्रुटि', data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash"></i> हटाएं';
        }
    } catch (err) {
        showToast('error', 'नेटवर्क त्रुटि', err.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash"></i> हटाएं';
    }
}

function showError(msg) {
    document.getElementById('loadingState').style.display = 'none';
    document.getElementById('mainContent').style.display  = 'none';
    document.getElementById('errorState').style.display   = 'block';
    document.getElementById('errorMessage').textContent   = msg;
}

function showToast(type, title, msg) {
    const tc    = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-c ${type}`;
    toast.innerHTML = `
        <div class="t-icon ${type}"><i class="fas fa-${type==='success'?'check':'exclamation-triangle'}"></i></div>
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

function shortDate(str) {
    if (!str) return '—';
    return new Date(str).toLocaleDateString('hi-IN', { day:'numeric', month:'short', year:'numeric' });
}

function fullDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('hi-IN', { day:'numeric', month:'long', year:'numeric' })
         + ' • ' + d.toLocaleTimeString('hi-IN', { hour:'2-digit', minute:'2-digit' });
}

function escHtml(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

@endsection
