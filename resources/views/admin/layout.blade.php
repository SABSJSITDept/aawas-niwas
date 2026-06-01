<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet" />


  <style>
    :root{
      --sidebar-w: 260px;
      --header-h: 64px;
      --footer-h: 48px;
      --navy-1: #000000;
      --navy-2: #141414;
      --muted-bg: #f3f7fb;
    }
    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;background:var(--muted-bg);color:#102a43;}

    /* ============ SIDEBAR ============ */
    .sidebar {
      position: fixed;
      left: 0; top: 0; bottom: 0;
      width: var(--sidebar-w);
      background: linear-gradient(180deg, var(--navy-1), var(--navy-2));
      color: #e6eef8;
      padding: 18px 14px;
      transition: transform .32s ease, width .32s ease;
      z-index: 1200;
      overflow: hidden; /* hide by default; inner .sidebar-body will scroll */
      box-shadow: 6px 0 20px rgba(3,12,27,0.25);
    }
    .sidebar.closed { transform: translateX(-110%); } /* fully hidden */
    .sidebar { position: fixed; left:0; top:0; bottom:0; width:var(--sidebar-w); }

    /* make a separate scrolling area inside sidebar */
    .sidebar-body {
      height: calc(100vh - 40px); /* reserve some top/bottom padding */
      overflow-y: auto;
      padding-bottom: 52px;
      padding-right: 6px; /* space for scrollbar */
    }

    .brand-compact { display:flex;align-items:center;gap:10px;padding-bottom:10px; }
    .brand-compact img{ width:44px;height:44px;border-radius:8px;object-fit:cover;border:2px solid rgba(255,255,255,0.06); }
    .brand-compact .title { font-weight:700; font-size:14px; color:#ffffff; letter-spacing:0.2px; }
    .brand-compact .sub { font-size:12px; color:rgba(230,238,248,0.8); }

    .sidebar .menu { margin-top:14px; }
    .menu-section { margin-top:12px; padding-top:6px; border-top:1px solid rgba(255,255,255,0.03); }
    .menu .menu-item {
      display:flex;align-items:center;gap:12px;padding:10px;border-radius:8px;color:rgba(230,238,248,0.95);
      text-decoration:none;font-weight:600;font-size:14px;margin-bottom:6px;
    }
    .menu .menu-item:hover { background: rgba(255,255,255,0.04); color:#fff; text-decoration:none; }
    .menu .menu-item i{ width:22px; text-align:center; font-size:18px; color: #cfe8ff; }

    .submenu {
     padding-left: 36px;
     }

    .submenu a { display:block; padding:8px 0; color:rgba(220,230,245,0.9); text-decoration:none; font-weight:500; font-size:13px; }
    .submenu a:hover { color:#fff; }

    /* Sidebar scrollbar (dark theme) */
    .sidebar-body::-webkit-scrollbar { width:10px; }
    .sidebar-body::-webkit-scrollbar-track { background: transparent; }
    .sidebar-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius:8px; border: 2px solid transparent; background-clip: padding-box; }
    .sidebar-body::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.12); }

    /* ============ MAIN (to the right of sidebar) ============ */
    .main {
      margin-left: var(--sidebar-w);
      transition: margin-left .32s ease;
      min-height:100vh;
      display:flex;
      flex-direction:column;
    }
    .main.full { margin-left: 0; }

    /* ============ TOPBAR ============ */
    .topbar {
  height: var(--header-h);
  display:flex;
  align-items:center;
  gap:14px;
  padding: 0 20px;
  background: #ffffff;
  border-bottom: 3px solid #000;   /* 🔥 Black border */
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  position: sticky;
  top: 0;
  z-index: 1100;
}
    .toggle-wrap { display:flex;align-items:center;gap:12px; }
    .toggle-btn {
      width:42px;height:42px;border-radius:8px;border:none;background:#fff;display:inline-flex;align-items:center;justify-content:center;
      box-shadow:0 2px 6px rgba(11,20,30,0.06); cursor:pointer; font-size:18px;
    }
    .page-heading { font-size:20px;font-weight:700;color:#0b2a44;margin:0; }

    /* ============ HEADER NAVIGATION ============ */
    .header-nav {
      display:flex;
      gap:8px;
      align-items:center;
      flex:1;
    }
    .header-nav-item {
      position:relative;
      display:inline-block;
    }
    .header-nav-btn {
      padding:8px 14px;
      background:#f8f9fa;
      border:1px solid #dee2e6;
      border-radius:6px;
      font-size:13px;
      font-weight:600;
      color:#0b2a44;
      cursor:pointer;
      transition:all .2s ease;
    }
    .header-nav-btn:hover {
      background:#e9ecef;
      color:#000;
    }
    .header-dropdown {
      position:absolute;
      top:100%;
      left:0;
      background:#fff;
      border:1px solid #dee2e6;
      border-radius:6px;
      min-width:220px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      display:none;
      margin-top:4px;
      z-index:1050;
    }
    .header-nav-item:hover .header-dropdown {
      display:block;
    }
    .header-dropdown a, .header-dropdown button {
      display:block;
      width:100%;
      padding:10px 14px;
      background:none;
      border:none;
      text-align:left;
      color:#0b2a44;
      text-decoration:none;
      font-size:13px;
      cursor:pointer;
      transition:all .2s ease;
    }
    .header-dropdown a:hover, .header-dropdown button:hover {
      background:#f8f9fa;
      color:#000;
      padding-left:18px;
    }
    .header-dropdown a:first-child {
      border-radius:6px 6px 0 0;
    }
    .header-dropdown a:last-child,
    .header-dropdown button:last-child {
      border-radius:0 0 6px 6px;
    }

    /* ============ CONTENT ============ */
    /* Content area gets its own scroll separate from sidebar.
       We set a calc height so footer stays visible and content scrolls inside this area. */
    .content-wrap {
      padding:20px 28px 20px 28px;
      flex:1;
      background:var(--muted-bg);
      overflow-y: auto;
      height: calc(100vh - var(--header-h) - var(--footer-h)); /* ensures internal scrolling */
    }

    /* Content scrollbar (light theme) */
    .content-wrap::-webkit-scrollbar { width:10px; }
    .content-wrap::-webkit-scrollbar-track { background: transparent; }
    .content-wrap::-webkit-scrollbar-thumb { background: rgba(15,32,48,0.12); border-radius:8px; border:2px solid transparent; background-clip: padding-box; }
    .content-wrap::-webkit-scrollbar-thumb:hover { background: rgba(15,32,48,0.18); }

    .row-cards { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px; align-items:start; }
    .stat-card { background:#fff;border-radius:12px;padding:22px;box-shadow: 0 8px 20px rgba(11,20,30,0.04); min-height:96px; display:flex; justify-content:space-between; align-items:center; }
    .chart-card { background:#fff;border-radius:12px;padding:18px;box-shadow:0 8px 20px rgba(11,20,30,0.04); min-height:220px; }

    /* ============ FOOTER ============ */
  .site-footer {
  height: var(--footer-h);
  display:flex;
  align-items:center;
  justify-content:center;
  background: #fff;
  border-top: 3px solid #000;   /* 🔥 Black border */
  color:#333;
  font-size:13px;
  font-weight:500;
}

    /* responsive adjustments */
    @media (max-width: 1000px){
      :root { --sidebar-w: 220px; }
    }
    @media (max-width: 780px){
      .sidebar { width: 84%; }
      .main { margin-left: 0; }
      .content-wrap { height: calc(100vh - var(--header-h) - var(--footer-h)); }
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- SIDEBAR -->
  <aside id="sidebar" class="sidebar closed" aria-label="Sidebar">
    <div class="brand-compact">
      <img src="{{ asset('images/logo.jpeg') }}" alt="logo">
      <div>
        <div class="title">SABSJS आवास-निवास </div>
        <div class="sub">Admin Dashboard</div>
      </div>
    </div>

    <!-- Separate scrolling body inside sidebar -->
    <div class="sidebar-body">
      <nav class="menu">
        <div class="menu-section">
          <a href="{{ route('admin.dashboard') }}" class="menu-item active">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </div>

        <div class="menu-section">
          <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">REGISTRATION</div>
          <button class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                  type="button" data-bs-toggle="collapse" data-bs-target="#menuStudent" aria-expanded="false">
            <span><i class="bi bi-person-badge"></i> Total Registration</span>
            <i class="bi bi-caret-down-fill"></i>
          </button>
          <div id="menuStudent" class="submenu collapse">
            <a href="{{ route('registration.list') }}">All Booking</a>
            <a href="{{ route('registration.completed_list_api') }}">Alloted Booking</a>
            <a href="{{ route('registration.checkout_list_api') }}">Check-Out Booking</a>
            <a href="{{ route('registration.rejected_list_api') }}">Rejected Bookings</a>
          
          </div>
          
          <!-- Family Booking Excel Upload -->
          <a href="{{ route('admin.family-booking.excel-upload') }}" class="menu-item">
            <i class="bi bi-cloud-upload text-success"></i> 
            <span>Bulk Upload Excel</span>
          </a>
        </div>

<div class="menu-section">
  <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">HOTELS</div>

  <button class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menuHotels"
          aria-expanded="false"
          aria-controls="menuHotels">
    <span><i class="bi bi-building"></i> Manage Hotels </span>
    <i class="bi bi-caret-down-fill"></i>
  </button>

  <div id="menuHotels" class="submenu collapse ps-3">
    <a href="{{ route('hotel.create') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-plus-square"></i> Add Hotel
    </a>
    <a href="{{ route('hotel.index') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-eye"></i> View Hotel
    </a>
  </div>
</div>


<div class="menu-section">
  <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">BHOJANSHALA</div>

  <button class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menuBhojan"
          aria-expanded="false"
          aria-controls="menuBhojan">
    <span><i class="bi bi-building"></i> BhojanShala Reprt Datewise </span>
    <i class="bi bi-caret-down-fill"></i>
  </button>

  <div id="menuBhojan" class="submenu collapse ps-3">
    <a href="{{ route('bhojanshala.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-plus-square"></i> View
    </a>
     </div>
</div>



<div class="menu-section">
  <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">REPORTS</div>

  <button class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menuReports"
          aria-expanded="false"
          aria-controls="menuReports">
    <span><i class="bi bi-folder2-open"></i> View And Download All The Reports </span>
    <i class="bi bi-caret-down-fill"></i>
  </button>

  <div id="menuReports" class="submenu collapse ps-3">
    <a href="{{ route('select.hotel') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-building"></i> All Hotel Details
    </a>
    <a href="{{ route('rooms.export.all') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-file-earmark-excel text-success"></i> All Hotel Availability Excel Report
    </a>
    <a href="{{ url('/admin/room-features-page') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-file-earmark-pdf text-danger"></i> Hotel Wise Room Features Report
    </a>
    <a href="{{ route('admin.room.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-calendar-check text-primary"></i> Booked Room Report
    </a>
    <a href="{{ route('family.members.export') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-people-fill text-info"></i> Family Members Excel Report
    </a>
    <a href="{{ route('group.members.export') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-people text-warning"></i> Group Members Excel Report
    </a>
    <a href="{{ route('admin.room.booking.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-calendar2-week text-primary"></i> Hotel Datewise Booking Report
    </a>  
    <a href="{{ route('room.checkin.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-journal-text text-secondary"></i> Datewise Registration Report
    </a>  
    <a href="{{ route('daily.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-clock-history text-dark"></i> Daily Stay Report
    </a>  
    <a href="{{ route('admin.checkin.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-door-open text-success"></i> Datewise Check In Check Out Report
    </a>
    <a href="{{ route('admin.parivahan.datewise.report') }}" class="d-flex align-items-center gap-2">
      <i class="bi bi-truck text-danger"></i> Parivahan Report
    </a>
  </div>
</div>


  
        <div class="menu-section">
          <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">SPIRITUAL MEMBERS</div>
          <a href="{{ route('admin.sadhu-sadvi.index') }}" class="menu-item"><i class="bi bi-people-fill"></i> Sadhu/Sadvi Management</a>
        </div>

        <div class="menu-section">
          <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">NEWS AND EVENTS</div>
          <a href="{{ route('admin.news.create') }}" class="menu-item"><i class="bi bi-file-earmark-text"></i> Add News Or Event</a>
        </div>

        <div class="menu-section">
          <div style="font-size:12px;color:rgba(230,238,248,0.6);font-weight:700;margin-bottom:8px;">FEEDBACK</div>
          <a href="{{ route('admin.feedback.index') }}" class="menu-item"><i class="bi bi-file-earmark-text"></i> See All Feedback</a>
        </div>

        <div style="height:14px"></div>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="menu-item btn btn-blank w-100 text-start">
            <i class="bi bi-box-arrow-right"></i> Logout
          </button>
        </form>
      </nav>
    </div> <!-- /.sidebar-body -->
  </aside>

  <!-- MAIN -->
  <div id="main" class="main full">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="toggle-wrap">
        <button id="toggleBtn" class="toggle-btn" title="Toggle sidebar">
          <i id="toggleIcon" class="bi bi-x-lg"></i>
        </button>
      </div>


      <!-- HEADER NAVIGATION -->
      <nav class="header-nav ms-auto">
        <!-- Dashboard -->
        <div class="header-nav-item">
          <a href="{{ route('admin.dashboard') }}" class="header-nav-btn">
            <i class="bi bi-grid-fill"></i> Dashboard
          </a>
        </div>

        <!-- Registration Dropdown -->
        <div class="header-nav-item">
          <button class="header-nav-btn">
            <i class="bi bi-person-badge"></i> Registration
          </button>
          <div class="header-dropdown">
            <a href="{{ route('registration.list') }}">All Booking</a>
            <a href="{{ route('registration.completed_list_api') }}">Alloted Booking</a>
            <a href="{{ route('registration.checkout_list_api') }}">Check-Out Booking</a>
            <a href="{{ route('registration.rejected_list_api') }}">Rejected Bookings</a>
            <a href="{{ route('admin.family-booking.excel-upload') }}">Bulk Upload Excel</a>
          </div>
        </div>

        <!-- Hotels Dropdown -->
        <div class="header-nav-item">
          <button class="header-nav-btn">
            <i class="bi bi-building"></i> Hotels
          </button>
          <div class="header-dropdown">
            <a href="{{ route('hotel.create') }}"><i class="bi bi-plus-square"></i> Add Hotel</a>
            <a href="{{ route('hotel.index') }}"><i class="bi bi-eye"></i> View Hotel</a>
          </div>
        </div>

        <!-- BhojanShala Dropdown -->
        <div class="header-nav-item">
          <button class="header-nav-btn">
            <i class="bi bi-building"></i> BhojanShala
          </button>
          <div class="header-dropdown">
            <a href="{{ route('bhojanshala.report') }}"><i class="bi bi-plus-square"></i> View Report</a>
          </div>
        </div>

        <!-- Reports Dropdown -->
        <div class="header-nav-item">
          <button class="header-nav-btn">
            <i class="bi bi-folder2-open"></i> Reports
          </button>
          <div class="header-dropdown">
            <a href="{{ route('select.hotel') }}"><i class="bi bi-building"></i> All Hotel Details</a>
            <a href="{{ route('rooms.export.all') }}"><i class="bi bi-file-earmark-excel text-success"></i> Hotel Availability Excel</a>
            <a href="{{ url('/admin/room-features-page') }}"><i class="bi bi-file-earmark-pdf text-danger"></i> Room Features Report</a>
            <a href="{{ route('admin.room.report') }}"><i class="bi bi-calendar-check text-primary"></i> Booked Room Report</a>
            <a href="{{ route('family.members.export') }}"><i class="bi bi-people-fill text-info"></i> Family Members Report</a>
            <a href="{{ route('group.members.export') }}"><i class="bi bi-people text-warning"></i> Group Members Report</a>
            <a href="{{ route('admin.room.booking.report') }}"><i class="bi bi-calendar2-week text-primary"></i> Hotel Datewise Report</a>
            <a href="{{ route('room.checkin.report') }}"><i class="bi bi-journal-text text-secondary"></i> Registration Report</a>
            <a href="{{ route('daily.report') }}"><i class="bi bi-clock-history text-dark"></i> Daily Stay Report</a>
            <a href="{{ route('admin.checkin.report') }}"><i class="bi bi-door-open text-success"></i> Check In/Out Report</a>
            <a href="{{ route('admin.parivahan.datewise.report') }}"><i class="bi bi-truck text-danger"></i> Parivahan Report</a>
          </div>
        </div>

        <!-- Spiritual Members -->
        <div class="header-nav-item">
          <a href="{{ route('admin.sadhu-sadvi.index') }}" class="header-nav-btn">
            <i class="bi bi-people-fill"></i> Spiritual
          </a>
        </div>

        <!-- News & Events -->
        <div class="header-nav-item">
          <a href="{{ route('admin.news.create') }}" class="header-nav-btn">
            <i class="bi bi-file-earmark-text"></i> News
          </a>
        </div>

        <!-- Feedback -->
        <div class="header-nav-item">
          <a href="{{ route('admin.feedback.index') }}" class="header-nav-btn">
            <i class="bi bi-file-earmark-text"></i> Feedback
          </a>
        </div>

        <!-- Logout -->
        <div class="header-nav-item">
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="header-nav-btn text-danger">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </div>
      </nav>
    </header>

    <!-- CONTENT (has its own scroll) -->
    

      <!-- main page content insertion -->
      <div class="mt-4 card-surface">
        @yield('content')
      </div>
    </div>

    <!-- FOOTER -->
   <footer class="site-footer">
  &copy; 2025 श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ. केंद्र कार्यालय IT DEPARTMENT 

  <!-- Info Button -->
  <button type="button" class="btn btn-sm btn-outline-dark ms-2" data-bs-toggle="modal" data-bs-target="#infoModal">
    <i class="bi bi-info-circle"></i>
  </button>
</footer>

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="infoModalLabel"><i class="bi bi-person-circle"></i> Contact Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong>IT Department</p>
        <p><strong>Mobile:</strong> +91 9636501008</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

  <script>
    (function(){
      const sidebar = document.getElementById('sidebar');
      const main = document.getElementById('main');
      const toggleBtn = document.getElementById('toggleBtn');
      const toggleIcon = document.getElementById('toggleIcon');

      toggleBtn.addEventListener('click', () => {
        const closed = sidebar.classList.toggle('closed');
        main.classList.toggle('full');
        toggleIcon.classList.toggle('bi-x-lg');
        toggleIcon.classList.toggle('bi-list');

        if(closed){
          document.querySelectorAll('.submenu.show').forEach(s => {
            const bs = bootstrap.Collapse.getInstance(s);
            if(bs) bs.hide();
          });
        }
      });

      // rotate caret on collapse toggles for visual cue
      document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn=>{
        btn.addEventListener('click', ()=>{
          const icon = btn.querySelector('.bi-caret-down-fill');
          const target = document.querySelector(btn.getAttribute('data-bs-target'));
          setTimeout(()=> {
            if(target.classList.contains('show')) icon.style.transform = 'rotate(180deg)';
            else icon.style.transform = 'rotate(0deg)';
          }, 200);
        });
      });

      // responsive: hide sidebar by default on small screens
      function handleResize(){
        if(window.innerWidth < 780){
          sidebar.classList.add('closed');
          main.classList.add('full');
          toggleIcon.classList.remove('bi-list');
          toggleIcon.classList.add('bi-x-lg');
        }
      }
      handleResize();
      window.addEventListener('resize', handleResize);
    })();
  </script>
  @stack('scripts')
</body>
</html>
