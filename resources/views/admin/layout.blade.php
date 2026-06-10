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
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    a, button { text-decoration: none !important; }
    
    /* Header Nav Specific */
    .header-nav-link { color: #475569; }
    .header-nav-link:hover, .header-nav-link[aria-expanded="true"] { color: #1e293b; background: #f1f5f9; }
    
    /* Bootstrap dropdown overrides for Tailwind styling */
    .dropdown-menu { border: 1px solid #e2e8f0; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); padding: 0.5rem 0; margin-top: 0.5rem !important; }
    .dropdown-item { font-size: 13px; font-weight: 500; color: #475569; padding: 0.5rem 1rem; transition: all 0.2s; }
    .dropdown-item:hover, .dropdown-item:focus { background-color: #f8fafc; color: #4f46e5; }
  </style>
  @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800">

  <!-- TOP HEADER (Full Width, No Sidebar) -->
  <header class="bg-white border-b border-slate-200 z-40 shadow-sm sticky top-0">
    
    <div class="flex flex-wrap items-center justify-between px-4 lg:px-8 py-3 gap-y-3">
        
        <!-- Brand -->
        <div class="flex items-center gap-3 shrink-0 mr-4">
            <img src="{{ asset('images/logo.jpeg') }}" alt="logo" class="w-10 h-10 rounded-md object-cover shadow-sm border border-slate-100">
            <div class="flex flex-col">
                <span class="text-slate-800 font-bold tracking-wide text-[13px] md:text-sm leading-tight font-['Inter']">SABSJS आवास-निवास</span>
                <span class="text-[10px] md:text-[11px] text-indigo-500 font-bold uppercase tracking-widest">Admin Panel</span>
            </div>
        </div>

        <!-- MAIN NAVIGATION (Centered/Flex) -->
        <div class="flex-1 flex items-center flex-wrap gap-1 md:gap-2">
            
            <a href="{{ route('admin.dashboard') }}" class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors">
                <i class="bi bi-grid-fill text-indigo-500"></i> <span class="hidden lg:inline">Dashboard</span>
            </a>

            <!-- Registration Dropdown -->
            <div class="dropdown inline-block">
                <button class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors bg-transparent border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-badge text-blue-500"></i> <span class="hidden lg:inline">Registration</span> <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('registration.all_list_api') }}" class="dropdown-item">All Registrations</a>
                    <a href="{{ route('registration.list') }}" class="dropdown-item">All Booking</a>
                    <a href="{{ route('registration.completed_list_api') }}" class="dropdown-item">Alloted Booking</a>
                    <a href="{{ route('registration.checkout_list_api') }}" class="dropdown-item">Check-Out Booking</a>
                    <a href="{{ route('registration.rejected_list_api') }}" class="dropdown-item">Rejected Bookings</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.family-booking.excel-upload') }}" class="dropdown-item text-emerald-600"><i class="bi bi-cloud-upload me-1"></i> Bulk Upload Excel</a>
                </div>
            </div>

            <!-- Hotels Dropdown -->
            <div class="dropdown inline-block">
                <button class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors bg-transparent border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-building text-amber-500"></i> <span class="hidden lg:inline">Hotels</span> <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('hotel.create') }}" class="dropdown-item"><i class="bi bi-plus-square me-1"></i> Add Hotel</a>
                    <a href="{{ route('hotel.index') }}" class="dropdown-item"><i class="bi bi-eye me-1"></i> View Hotel</a>
                </div>
            </div>

            <!-- Bhojanshala Dropdown -->
            <div class="dropdown inline-block">
                <button class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors bg-transparent border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-cup-hot text-orange-500"></i> <span class="hidden lg:inline">Bhojanshala</span> <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('bhojanshala.report') }}" class="dropdown-item"><i class="bi bi-eye me-1"></i> View Report</a>
                </div>
            </div>

            <!-- Reports Dropdown -->
            <div class="dropdown inline-block">
                <button class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors bg-transparent border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-folder2-open text-purple-500"></i> <span class="hidden lg:inline">Reports</span> <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('select.hotel') }}" class="dropdown-item"><i class="bi bi-building text-slate-400 me-2"></i> All Hotel Details</a>
                    <a href="{{ route('rooms.dashboard') }}" class="dropdown-item"><i class="bi bi-speedometer2 text-info me-2"></i> Availability Dashboard</a>
                    <a href="{{ url('/admin/room-features-page') }}" class="dropdown-item"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Room Features Report</a>
                    <a href="{{ route('admin.room.report') }}" class="dropdown-item"><i class="bi bi-calendar-check text-indigo-400 me-2"></i> Booked Room Report</a>
                    <a href="{{ route('family.members.export') }}" class="dropdown-item"><i class="bi bi-people-fill text-info me-2"></i> Family Members Excel</a>
                    <a href="{{ route('group.members.export') }}" class="dropdown-item"><i class="bi bi-people text-warning me-2"></i> Group Members Excel</a>
                    <a href="{{ route('room.checkin.report') }}" class="dropdown-item"><i class="bi bi-journal-text text-secondary me-2"></i> Registration Report</a>
                    <a href="{{ route('admin.checkin.report') }}" class="dropdown-item"><i class="bi bi-door-open text-emerald-400 me-2"></i> Check In/Out Report</a>
                    <a href="{{ route('admin.parivahan.datewise.report') }}" class="dropdown-item"><i class="bi bi-truck text-rose-400 me-2"></i> Parivahan Report</a>
                </div>
            </div>

            <!-- Direct Links -->
            <a href="{{ route('admin.sadhu-sadvi.index') }}" class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors">
                <i class="bi bi-people-fill text-pink-500"></i> <span class="hidden xl:inline">Spiritual</span>
            </a>
            <a href="{{ route('admin.news.create') }}" class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors">
                <i class="bi bi-megaphone-fill text-sky-500"></i> <span class="hidden xl:inline">News</span>
            </a>

            <!-- Settings Dropdown -->
            <div class="dropdown inline-block">
                <button class="header-nav-link inline-flex items-center gap-1.5 px-3 py-2 rounded-md text-[13px] font-semibold transition-colors bg-transparent border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-gear-fill text-slate-500"></i> <span class="hidden lg:inline">Content</span> <i class="bi bi-chevron-down text-[10px]"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.settings.index') }}" class="dropdown-item"><i class="bi bi-gear me-1"></i> Site Settings</a>
                    <a href="{{ route('admin.helplines.index') }}" class="dropdown-item"><i class="bi bi-telephone-fill me-1"></i> Helplines</a>
                    <a href="{{ route('admin.parking.index') }}" class="dropdown-item"><i class="bi bi-p-circle-fill me-1"></i> Parking</a>
                </div>
            </div>

        </div>

        <!-- Right Side: Profile / Logout -->
        <div class="flex items-center gap-3 shrink-0 ml-auto pl-2 md:pl-4 md:border-l border-slate-200">
            <div class="dropdown">
                <button class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="w-8 h-8 rounded-md bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-sm">
                        <i class="bi bi-person-fill text-sm"></i>
                    </div>
                    <span class="hidden sm:inline font-['Inter']">Administrator</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-xl border-slate-100 rounded-xl py-2 w-48">
                    <a href="{{ route('admin.settings.index') }}" class="dropdown-item flex items-center gap-2"><i class="bi bi-gear"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item flex items-center gap-2 text-rose-600 hover:bg-rose-50"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
  </header>

  <!-- CONTENT AREA -->
  <main class="flex-1 w-full max-w-[1600px] mx-auto p-4 md:p-6 lg:p-8">
      @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="h-16 bg-white border-t border-slate-200 shrink-0 flex items-center justify-between px-4 md:px-8 mt-auto">
      <p class="text-xs md:text-sm text-slate-500 font-medium m-0">&copy; 2025 श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ.</p>
      <button type="button" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 border-0 flex items-center justify-center transition-colors" data-bs-toggle="modal" data-bs-target="#infoModal" title="Contact Info">
          <i class="bi bi-info-circle-fill"></i>
      </button>
  </footer>

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

  <!-- Bootstrap JS (Provides Dropdown, Collapse, Modal functionality) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

  @stack('scripts')
</body>
</html>
