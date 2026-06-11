<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  
  <!-- SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      corePlugins: { preflight: false },
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'sans-serif'] },
        }
      }
    }
  </script>

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; margin: 0; }
    
    /* Scrollbars */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    a, button { text-decoration: none !important; }

    /* Fix Bootstrap and Tailwind CSS conflict for .collapse */
    .collapse.show, .collapsing { visibility: visible !important; }

    /* Sidebar nav links */
    .nav-item { margin-bottom: 0.25rem; }
    .nav-link-custom {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      color: #64748b;
      font-weight: 500;
      font-size: 0.875rem;
      border-radius: 0.5rem;
      transition: all 0.2s;
    }
    .nav-link-custom:hover {
      background-color: #f1f5f9;
      color: #0f172a;
    }
    .nav-link-custom.active {
      background-color: #4f46e5;
      color: white !important;
      box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
    }
    .nav-link-custom.active i {
      color: white !important;
    }
    .nav-link-custom.parent-active {
      background-color: #f8fafc;
      color: #4f46e5;
      font-weight: 600;
    }
    .nav-link-custom[aria-expanded="true"] {
      background-color: #f8fafc;
      color: #4f46e5;
    }
    .nav-link-custom[aria-expanded="true"] .bi-chevron-down {
      transform: rotate(180deg);
    }
    .bi-chevron-down {
      transition: transform 0.2s;
    }
    
    .submenu {
      padding-left: 2.5rem;
      padding-top: 0.25rem;
      padding-bottom: 0.25rem;
    }
    .submenu-link {
      display: block;
      padding: 0.5rem 0.75rem;
      color: #475569;
      font-size: 0.8125rem;
      font-weight: 500;
      text-decoration: none;
      border-radius: 0.375rem;
      transition: all 0.2s;
    }
    .submenu-link:hover {
      color: #4f46e5;
      background-color: #f1f5f9;
    }
    .submenu-link.active {
      color: #4f46e5;
      background-color: #e0e7ff;
      font-weight: 600;
    }

    /* Layout */
    .sidebar {
      width: 260px;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 40;
      overflow-y: auto;
      border-right: 1px solid #e2e8f0;
      background: white;
    }
    .main-wrapper {
      margin-left: 260px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .main-wrapper {
        margin-left: 0;
      }
    }
    
    /* Bootstrap dropdown overrides for Tailwind styling */
    .dropdown-menu { border: 1px solid #e2e8f0; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); padding: 0.5rem 0; margin-top: 0.5rem !important; }
    .dropdown-item { font-size: 13px; font-weight: 500; color: #475569; padding: 0.5rem 1rem; transition: all 0.2s; }
    .dropdown-item:hover, .dropdown-item:focus { background-color: #f8fafc; color: #4f46e5; }
  </style>
  @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Sidebar Overlay -->
  <div class="fixed inset-0 bg-slate-900/50 z-30 hidden md:hidden" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <aside class="sidebar shadow-sm flex flex-col" id="sidebar">
    <!-- Brand -->
    <div class="h-16 flex items-center gap-3 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white shrink-0">
        <div class="w-8 h-8 rounded bg-white p-0.5 flex items-center justify-center">
            <img src="{{ asset('images/logo.jpeg') }}" alt="logo" class="max-w-full max-h-full rounded-sm object-cover">
        </div>
        <div class="flex flex-col">
            <span class="font-bold tracking-wide text-sm leading-tight">SABSJS</span>
            <span class="text-[10px] text-indigo-100 font-medium tracking-widest uppercase">Admin Panel</span>
        </div>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
            A
        </div>
        <div>
            <div class="text-sm font-bold text-slate-800">Administrator</div>
            <div class="text-[11px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded-full inline-block mt-0.5">Super Admin</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1">
      
      <!-- Dashboard -->
      <div class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-grid-fill text-indigo-500 text-lg"></i>
          <span class="flex-1">Dashboard</span>
        </a>
      </div>

      <div class="text-xs font-bold text-slate-400 px-3 mt-4 mb-2 uppercase tracking-wider">Bookings</div>

      @php
          $isRegistrationActive = request()->routeIs('registration.*') || request()->routeIs('admin.family-booking.*');
      @endphp
      <!-- Registration -->
      <div class="nav-item">
        <button class="nav-link-custom w-full border-0 bg-transparent text-left {{ $isRegistrationActive ? 'parent-active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRegistration" aria-expanded="{{ $isRegistrationActive ? 'true' : 'false' }}">
          <i class="bi bi-person-badge text-blue-500 text-lg"></i>
          <span class="flex-1">Registration</span>
          <i class="bi bi-chevron-down text-[10px]"></i>
        </button>
        <div class="collapse {{ $isRegistrationActive ? 'show' : '' }}" id="collapseRegistration">
          <div class="submenu">
            <a href="{{ route('registration.all_list_api') }}" class="submenu-link {{ request()->routeIs('registration.all_list_api') ? 'active' : '' }}">All Registrations</a>
            <a href="{{ route('registration.list') }}" class="submenu-link {{ request()->routeIs('registration.list') ? 'active' : '' }}">All Booking</a>
            <a href="{{ route('registration.completed_list_api') }}" class="submenu-link {{ request()->routeIs('registration.completed_list_api') ? 'active' : '' }}">Alloted Booking</a>
            <a href="{{ route('registration.checkout_list_api') }}" class="submenu-link {{ request()->routeIs('registration.checkout_list_api') ? 'active' : '' }}">Check-Out Booking</a>
            <a href="{{ route('registration.rejected_list_api') }}" class="submenu-link {{ request()->routeIs('registration.rejected_list_api') ? 'active' : '' }}">Rejected Bookings</a>
            <a href="{{ route('admin.family-booking.excel-upload') }}" class="submenu-link text-emerald-600 {{ request()->routeIs('admin.family-booking.excel-upload') ? 'active' : '' }}"><i class="bi bi-cloud-upload me-1"></i> Bulk Upload</a>
          </div>
        </div>
      </div>

      @php
          $isHotelsActive = request()->routeIs('hotel.*');
      @endphp
      <!-- Hotels -->
      <div class="nav-item">
        <button class="nav-link-custom w-full border-0 bg-transparent text-left {{ $isHotelsActive ? 'parent-active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHotels" aria-expanded="{{ $isHotelsActive ? 'true' : 'false' }}">
          <i class="bi bi-building text-amber-500 text-lg"></i>
          <span class="flex-1">Hotels</span>
          <i class="bi bi-chevron-down text-[10px]"></i>
        </button>
        <div class="collapse {{ $isHotelsActive ? 'show' : '' }}" id="collapseHotels">
          <div class="submenu">
            <a href="{{ route('hotel.create') }}" class="submenu-link {{ request()->routeIs('hotel.create') ? 'active' : '' }}"><i class="bi bi-plus-square me-1"></i> Add Hotel</a>
            <a href="{{ route('hotel.index') }}" class="submenu-link {{ request()->routeIs('hotel.index') ? 'active' : '' }}"><i class="bi bi-eye me-1"></i> View Hotel</a>
          </div>
        </div>
      </div>

      <div class="text-xs font-bold text-slate-400 px-3 mt-4 mb-2 uppercase tracking-wider">Reports & Data</div>

      @php
          $isBhojanshalaActive = request()->routeIs('bhojanshala.*');
      @endphp
      <!-- Bhojanshala -->
      <div class="nav-item">
        <button class="nav-link-custom w-full border-0 bg-transparent text-left {{ $isBhojanshalaActive ? 'parent-active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBhojanshala" aria-expanded="{{ $isBhojanshalaActive ? 'true' : 'false' }}">
          <i class="bi bi-cup-hot text-orange-500 text-lg"></i>
          <span class="flex-1">Bhojanshala</span>
          <i class="bi bi-chevron-down text-[10px]"></i>
        </button>
        <div class="collapse {{ $isBhojanshalaActive ? 'show' : '' }}" id="collapseBhojanshala">
          <div class="submenu">
            <a href="{{ route('bhojanshala.report') }}" class="submenu-link {{ request()->routeIs('bhojanshala.report') ? 'active' : '' }}">View Report</a>
          </div>
        </div>
      </div>

      @php
          $isReportsActive = request()->routeIs('select.hotel', 'rooms.dashboard', 'admin.room.report', 'family.members.export', 'group.members.export', 'room.checkin.report', 'admin.checkin.report', 'admin.parivahan.datewise.report') || request()->is('admin/room-features-page');
      @endphp
      <!-- Reports -->
      <div class="nav-item">
        <button class="nav-link-custom w-full border-0 bg-transparent text-left {{ $isReportsActive ? 'parent-active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="{{ $isReportsActive ? 'true' : 'false' }}">
          <i class="bi bi-folder2-open text-purple-500 text-lg"></i>
          <span class="flex-1">Reports</span>
          <i class="bi bi-chevron-down text-[10px]"></i>
        </button>
        <div class="collapse {{ $isReportsActive ? 'show' : '' }}" id="collapseReports">
          <div class="submenu">
            <a href="{{ route('select.hotel') }}" class="submenu-link {{ request()->routeIs('select.hotel') ? 'active' : '' }}">All Hotel Details</a>
            <a href="{{ route('rooms.dashboard') }}" class="submenu-link {{ request()->routeIs('rooms.dashboard') ? 'active' : '' }}">Availability Dashboard</a>
            <a href="{{ url('/admin/room-features-page') }}" class="submenu-link {{ request()->is('admin/room-features-page') ? 'active' : '' }}">Room Features</a>
            <a href="{{ route('admin.room.report') }}" class="submenu-link {{ request()->routeIs('admin.room.report') ? 'active' : '' }}">Booked Room</a>
            <a href="{{ route('family.members.export') }}" class="submenu-link {{ request()->routeIs('family.members.export') ? 'active' : '' }}">Family Members Excel</a>
            <a href="{{ route('group.members.export') }}" class="submenu-link {{ request()->routeIs('group.members.export') ? 'active' : '' }}">Group Members Excel</a>
            <a href="{{ route('room.checkin.report') }}" class="submenu-link {{ request()->routeIs('room.checkin.report') ? 'active' : '' }}">Registration Report</a>
            <a href="{{ route('admin.checkin.report') }}" class="submenu-link {{ request()->routeIs('admin.checkin.report') ? 'active' : '' }}">Check In/Out</a>
            <a href="{{ route('admin.parivahan.datewise.report') }}" class="submenu-link {{ request()->routeIs('admin.parivahan.datewise.report') ? 'active' : '' }}">Parivahan Report</a>
          </div>
        </div>
      </div>

      <div class="text-xs font-bold text-slate-400 px-3 mt-4 mb-2 uppercase tracking-wider">Others</div>

      <!-- Spiritual -->
      <div class="nav-item">
        <a href="{{ route('admin.sadhu-sadvi.index') }}" class="nav-link-custom {{ request()->routeIs('admin.sadhu-sadvi.*') ? 'active' : '' }}">
          <i class="bi bi-people-fill text-pink-500 text-lg"></i>
          <span class="flex-1">Spiritual</span>
        </a>
      </div>

      <!-- News -->
      <div class="nav-item">
        <a href="{{ route('admin.news.create') }}" class="nav-link-custom {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
          <i class="bi bi-megaphone-fill text-sky-500 text-lg"></i>
          <span class="flex-1">News</span>
        </a>
      </div>

      @php
          $isSettingsActive = request()->routeIs('admin.settings.*', 'admin.helplines.*', 'admin.parking.*');
      @endphp
      <!-- Content / Settings -->
      <div class="nav-item">
        <button class="nav-link-custom w-full border-0 bg-transparent text-left {{ $isSettingsActive ? 'parent-active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettings" aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}">
          <i class="bi bi-gear-fill text-slate-500 text-lg"></i>
          <span class="flex-1">Content Settings</span>
          <i class="bi bi-chevron-down text-[10px]"></i>
        </button>
        <div class="collapse {{ $isSettingsActive ? 'show' : '' }}" id="collapseSettings">
          <div class="submenu">
            <a href="{{ route('admin.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Site Settings</a>
            <a href="{{ route('admin.dynamic-fields.index') }}" class="submenu-link {{ request()->routeIs('admin.dynamic-fields.*') ? 'active' : '' }}">Form Builder</a>
            <a href="{{ route('admin.helplines.index') }}" class="submenu-link {{ request()->routeIs('admin.helplines.*') ? 'active' : '' }}">Helplines</a>
            <a href="{{ route('admin.parking.index') }}" class="submenu-link {{ request()->routeIs('admin.parking.*') ? 'active' : '' }}">Parking</a>
          </div>
        </div>
      </div>

      <!-- Sign Out -->
      <div class="nav-item mt-8 border-t border-slate-100 pt-4">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="nav-link-custom w-full border-0 bg-transparent text-left text-rose-500 hover:bg-rose-50">
              <i class="bi bi-box-arrow-right text-rose-500 text-lg"></i>
              <span class="flex-1">Sign Out</span>
            </button>
        </form>
      </div>

    </nav>
  </aside>

  <!-- Main Wrapper -->
  <div class="main-wrapper">
    
    <!-- Header -->
    <header class="h-16 bg-white shadow-sm border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
      
      <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button class="md:hidden text-slate-500 hover:text-indigo-600 bg-slate-50 hover:bg-slate-100 w-9 h-9 rounded-md flex items-center justify-center border-0 transition-colors" id="sidebarToggle">
          <i class="bi bi-list text-xl"></i>
        </button>
        
        <h1 class="text-lg md:text-xl font-bold text-slate-800 m-0">Dashboard</h1>
      </div>

      <div class="flex items-center gap-4">
        <div class="text-sm font-medium text-slate-500 hidden sm:block">
            {{ date('D, d M Y') }}
        </div>
        
        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="flex items-center gap-2 px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors border-0" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="w-7 h-7 rounded-md bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="bi bi-person-fill text-sm"></i>
                </div>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-slate-100 rounded-xl py-2 w-48 mt-2">
                <a href="{{ route('admin.settings.index') }}" class="dropdown-item flex items-center gap-2"><i class="bi bi-gear"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item flex items-center gap-2 text-rose-600 hover:bg-rose-50"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-6 lg:p-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 px-4 md:px-8 flex items-center justify-between mt-auto">
        <p class="text-xs md:text-sm text-slate-500 font-medium m-0">&copy; {{ date('Y') }} श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ.</p>
        <button type="button" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 border-0 flex items-center justify-center transition-colors" data-bs-toggle="modal" data-bs-target="#infoModal" title="Contact Info">
            <i class="bi bi-info-circle-fill"></i>
        </button>
    </footer>
  </div>

  <!-- Info Modal -->
  <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-xl" style="border-radius: 1rem; overflow: hidden;">
        <div class="modal-header border-0 pb-0 pt-4 px-4 flex justify-between items-center bg-white">
          <h5 class="modal-title font-bold text-slate-800 font-['Inter'] m-0 flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="bi bi-headset"></i></div> 
              IT Department
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 bg-white">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider m-0 mb-1">Support Helpline</p>
                    <p class="text-lg font-extrabold text-slate-800 m-0">+91 9636501008</p>
                </div>
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
    // Sidebar Toggle Logic for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if(sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.add('show');
            sidebarOverlay.classList.remove('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.add('hidden');
        });
    }
  </script>

  @stack('scripts')
</body>
</html>
