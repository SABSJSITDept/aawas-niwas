<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel AJAX Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 4 Theme -->
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (without preflight to prevent breaking Bootstrap) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false,
        }
      }
    </script>


    <!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<style>
  select.form-select, input.form-control, textarea.form-control {
    border: 1px solid #ced4da !important;
    border-radius: 8px;
    padding: 10px 15px;
    transition: border-color 0.3s, box-shadow 0.3s;
    font-family: 'Poppins', sans-serif;
  }
  select.form-select:focus, input.form-control:focus, textarea.form-control:focus {
    border-color: #2575fc !important;
    box-shadow: 0 0 0 0.2rem rgba(37, 117, 252, 0.25) !important;
  }
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f3f4f6;
  }
  .card {
    border-radius: 12px;
    border: none;
  }
  .form-step { display: none; }
  .form-step.active { display: block; animation: fadeIn 0.5s; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  .step-indicator { display: flex; justify-content: space-between; margin-bottom: 30px; position: relative; padding: 0 20px; }
  .step-indicator::before { content: ''; position: absolute; top: 18px; left: 40px; right: 40px; height: 3px; background: #e5e7eb; z-index: 1; border-radius: 3px; }
  .step-indicator .step { z-index: 2; background: white; border: 3px solid #e5e7eb; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #9ca3af; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
  .step-indicator .step.active { border-color: #2575fc; background: #2575fc; color: white; transform: scale(1.1); box-shadow: 0 4px 10px rgba(37, 117, 252, 0.3); }
  .step-indicator .step.completed { border-color: #10b981; background: #10b981; color: white; }
  
  .btn-primary { background: linear-gradient(90deg, #2575fc, #6a11cb); border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(37, 117, 252, 0.2); transition: transform 0.2s, box-shadow 0.2s; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(37, 117, 252, 0.3); background: linear-gradient(90deg, #1b63da, #570eb0); }
  .btn-success { background: linear-gradient(90deg, #10b981, #059669); border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); transition: transform 0.2s, box-shadow 0.2s; }
  .btn-success:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3); background: linear-gradient(90deg, #0d9668, #047857); }
  .btn-secondary { background: #6b7280; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(107, 114, 128, 0.2); transition: transform 0.2s; }
  .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(107, 114, 128, 0.3); background: #4b5563; }
</style>



</head>
<body>

<!-- ✅ Full-Screen API Loading Overlay -->
<div id="apiLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.55); backdrop-filter:blur(3px); -webkit-backdrop-filter:blur(3px); z-index:99999; justify-content:center; align-items:center; flex-direction:column;">
    <div style="background:white; border-radius:16px; padding:32px 48px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div class="spinner-border" style="width:3rem;height:3rem;border-width:4px;color:#2575fc;" role="status"></div>
        <p style="margin:14px 0 0; font-weight:600; font-size:1rem; color:#374151; font-family:'Poppins',sans-serif;">🔄 डेटा लोड हो रहा है...<br><small style="font-weight:400;color:#6b7280;">कृपया प्रतीक्षा करें</small></p>
    </div>
</div>

<script>
    function showApiLoader() { document.getElementById('apiLoadingOverlay').style.display = 'flex'; }
    function hideApiLoader() { document.getElementById('apiLoadingOverlay').style.display = 'none'; }
</script>

<nav class="navbar navbar-expand-lg shadow-md bg-white" style="z-index: 1050; border-bottom: 3px solid #f59e0b;">
  <div class="container-fluid px-4 d-flex justify-content-between align-items-center py-2">
    
    <!-- Left Side: Logo + Title -->
    <a class="navbar-brand d-flex align-items-center gap-3 text-gray-800 text-decoration-none" href="#">
      <img src="{{ asset('images/chaturmaslogo.png') }}" alt="Logo" width="55" height="55" class="rounded-circle shadow-sm border border-gray-200 p-1">
      <div class="d-flex flex-column lh-sm">
        <span class="fw-bold fs-4 text-indigo-700">समर्पण महोत्सव - 2026</span>
        <small class="fs-6 text-gray-500 font-medium">बीकानेर, राजस्थान </small>
      </div>
    </a>

    <!-- Right Side: Home Button -->
    <a href="{{ route('home') }}" class="btn btn-outline-indigo px-4 py-2 font-semibold rounded-lg shadow-sm transition hover:bg-indigo-50 border border-indigo-600 text-indigo-700 d-flex align-items-center gap-2">
      <i class="bi bi-house-door-fill"></i> Home
    </a>
    
  </div>
</nav>

     <section class="container my-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden relative">
        <!-- Decorative top line -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        <div class="p-6 md:p-8">
          <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-6">
            <!-- Left: Logo -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/chaturmaslogo.png') }}" alt="Logo" class="h-32 md:h-40 object-contain drop-shadow-md">
            </div>

            <!-- Center: Title -->
            <div class="flex-grow flex justify-center text-center">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 md:px-8 py-3 rounded-2xl shadow-lg transform hover:-translate-y-1 transition duration-300">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-wide m-0">समर्पण महोत्सव - 2026</h1>
                </div>
            </div>

            <!-- Right: Contact Info -->
            <div class="flex-shrink-0">
                <div class="bg-gray-50 p-4 rounded-xl shadow-md border-l-4 border-amber-500 min-w-[250px]">
                    <h6 class="font-bold text-indigo-700 mb-3 text-base flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        आवास-निवास संपर्क
                    </h6>
                    <div class="text-sm mb-2 flex justify-between"><span class="font-semibold text-gray-700">संपर्क 1:</span> <a href="tel:+919876543210" class="text-blue-600 hover:underline">+91 9876543210</a></div> 
                    <div class="text-sm flex justify-between"><span class="font-semibold text-gray-700">संपर्क 2:</span> <a href="tel:+919876543211" class="text-blue-600 hover:underline">+91 9876543211</a></div>
                </div>
            </div>
        </div>

   </section>
   <div class="container mb-5">
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 md:p-10 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
        @csrf   
        

<div class="container px-4"> <!-- Added padding -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">⚠️ त्रुटि!</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="bookingForm" action="{{ route('group.booking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Stepper UI -->
            <div class="step-indicator mb-4">
                <div class="step active" id="indicator-1">1</div>
                <div class="step" id="indicator-2">2</div>
                <div class="step" id="indicator-3">3</div>
            </div>

            <div class="form-step active" id="step-1">
                <h4 class="text-primary mb-3">व्यक्तिगत जानकारी (Personal Details)</h4>
                <div class="row g-3">

<!-- Mobile Number FIRST -->
<div class="col-md-3 mb-3">
    <label class="form-label fw-semibold"><span style="color: red;">*</span> मोबाईल नंबर</label>
    <div class="input-group">
        <input type="text" name="phone" id="head_phone" class="form-control" maxlength="10"
               placeholder="10 अंक दर्ज करें"
               oninput="this.value=this.value.replace(/[^0-9]/g,''); if(this.value.length===10) fetchHeadProfile(this.value);"
               required>
        <span class="input-group-text" id="headPhoneLoader" style="display:none;">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
        </span>
    </div>
    <small class="text-muted">10 अंक डालते ही डेटा auto-fill होगा</small>
</div>

<!-- Name field -->
<div class="col-md-3">
    <label class="form-label fw-semibold">
        <span style="color: red;">*</span> नाम
    </label>
    <input type="text" name="name" id="head_name" class="form-control name-field" placeholder="API से auto-fill या manually लिखें" required>
</div>

<!-- Relationship Type field -->
<div class="col-md-3">
    <label class="form-label fw-semibold">संबंध</label>
    <select name="relationship_type" class="form-select" >
        <option value="">चुनें</option>
        <option value="Son of">Son of</option>
        <option value="Daughter of">Daughter of</option>
        <option value="Wife of">Wife of</option>
    </select>
</div>

<!-- Father/Husband name field -->
<div class="col-md-3">
    <label class="form-label fw-semibold">
        <span style="color: red;">*</span> पिता/पति का नाम
    </label>
    <input type="text" name="father_name" id="head_father_name" class="form-control name-field" required>
</div>

              <div class="col-md-3 mb-3">
    <label class="form-label fw-semibold">शहर <span style="color: red;">*</span> </label>
    <div class="d-flex align-items-center">
        <select name="city" class="form-select" required></select>
        <div id="cityLoader" class="loader" style="display: none;"></div>
    </div>
</div>

<div class="col-md-3 mb-3">
    <label class="form-label fw-semibold">राज्य <span style="color: red;">*</span> </label>
    <div class="d-flex align-items-center">
        <select name="state" class="form-select" required></select>
        <div id="stateLoader" class="loader" style="display: none;"></div>
    </div>
</div>

<div class="col-md-3 mb-3">
    <label class="form-label fw-semibold">अंचल <span style="color: red;">*</span></label>
    <div class="d-flex align-items-center">
        <select name="aanchal" class="form-select" required></select>
        <div id="aanchalLoader" class="loader" style="display: none;"></div>
    </div>
</div>
                </div> <!-- End Step 1 row -->
                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-primary px-4 py-2" onclick="nextStep(1)">Next &rarr;</button>
                </div>
            </div> <!-- End Step 1 -->


            <div class="form-step" id="step-2">
                <h4 class="text-primary mb-3">सदस्यों की जानकारी (Member Details)</h4>
                <div class="row g-3">
            <div class="mb-3">
            <label class="form-label">आपके साथ आने वाले सदस्यों की संख्या </label>
            <input type="number" id="total_members" name="total_members" class="form-control" min="11" max="3000"  required>
            <small class="text-muted">Group booking के लिए न्यूनतम 11 सदस्य होने चाहिए।</small>
        </div>

        
        <div id="membersContainer"></div>
        
        <div class="col-md-4">
            <label class="form-label">कुल पुरुष  </label>
            <input type="number" name="total_male" class="form-control"  min="0" value="0" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">कुल महिला </label>
            <input type="number" name="total_female" class="form-control"  min="0" value="0" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">बच्चो की संख्या 10 साल तक की  </label>
            <input type="number" name="child_count" class="form-control"  min="0" value="0" required>
        </div>

        <!-- 60+ Members ka Option (Always Visible)  -->
        <div class="col-md-4">
    <label class="form-label">60 वर्ष से अधिक आयु का कोई व्यक्ति</label>
    <select id="60plus_option" class="form-control"  required>
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
</div>

<!-- 60+ Male & Female Count (Initially Hidden using d-none) -->
<div class="col-md-3 d-none" id="60plus_male">
    <label class="form-label">संख्या पुरुष</label>
    <input type="number" name="sixty_plus_male" class="form-control" min="0" value="0">
</div>

<div class="col-md-3 d-none" id="60plus_female">
    <label class="form-label">संख्या महिला</label>
    <input type="number" name="sixty_plus_female" class="form-control" min="0" value="0">
</div>

                </div> <!-- End Step 2 row -->
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="prevStep(2)">&larr; Previous</button>
                    <button type="button" class="btn btn-primary px-4 py-2" onclick="nextStep(2)">Next &rarr;</button>
                </div>
            </div> <!-- End Step 2 -->

            <div class="form-step" id="step-3">
                <h4 class="text-center text-primary mb-3"> यात्रा विवरण (Travel Details)</h4> 
                <div class="row g-3">
                <div class="col-md-4">
                     <label class="form-label">आने का वाहन</label>
                    <select name="travel_type" class="form-select"  required>
                        <option value="Train">Train</option>
                        <option value="Flight">Flight</option>
                        <option value="Bus">Bus</option>
                        <option value="Car">Car</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">आगमन की दिनांक</label>
                    <input type="date" name="check_in_date" class="form-control"  required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">प्रस्थान की दिनांक</label>
                    <input type="date" name="check_out_date" class="form-control"  required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">आगमन का समय</label>
                    <input type="time" name="check_in_time" class="form-control"   required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">प्रस्थान का समय</label>
                    <input type="time" name="check_out_time" class="form-control"  required>
                </div>


<div class="col-md-12 mb-3">
    <label class="form-label fw-semibold">रिमार्क (Remark)</label>
    <textarea name="remark" class="form-control" rows="3" maxlength="1000"  placeholder="यहाँ रिमार्क लिखें..."></textarea>
</div>
                </div> <!-- End Step 3 row -->
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="prevStep(3)">&larr; Previous</button>
                    <button type="submit" class="btn btn-success px-5 py-2 fw-bold" id="submitBtn">Submit Booking</button>
                </div>
            </div> <!-- End Step 3 -->
        </form>
    </div>
</div>
</div> 
<!-- Footer -->
<footer class="footer bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">&copy; 2025 श्री साधुमार्गी जैन संघ | All Rights Reserved.</p>
    </div>
</footer>
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ✅ Helper: Show/Hide full-screen overlay
function showApiLoader() {
    const el = document.getElementById('apiLoadingOverlay');
    if (el) el.style.display = 'flex';
}
function hideApiLoader() {
    const el = document.getElementById('apiLoadingOverlay');
    if (el) el.style.display = 'none';
}

// ✅ Helper: Fill City, State, Aanchal
function fillCityStateAanchal(p) {
    if (p.city) {
        $("select[name='city'] option").filter(function() { return $(this).text().trim() === p.city.trim(); }).prop("selected", true).trigger("change");
    }
    if (p.state) {
        $("select[name='state'] option").filter(function() { return $(this).text().trim() === p.state.trim(); }).prop("selected", true).trigger("change");
    }
    if (p.aanchal) {
        $("select[name='aanchal'] option").filter(function() { return $(this).text().trim() === p.aanchal.trim(); }).prop("selected", true).trigger("change");
    }
}

// ✅ Helper: Fill Age, Gender, MID, Aadhar
function fillExtraFields(p, container = document) {
    let ageInput = container.querySelector("input[name$='[age]']") || container.querySelector("input[name='age']");
    let midInput = container.querySelector("input[name$='[mid]']") || container.querySelector("input[name='mid']");
    let aadharInput = container.querySelector("input[name$='[aadhar_number]']") || container.querySelector("input[name='aadhar_number']");
    
    // Age handling: Calculate from birth_day if age is 0 or missing
    let finalAge = p.age;
    if ((!finalAge || finalAge == 0) && p.birth_day) {
        let birthDate = new Date(p.birth_day);
        let today = new Date();
        let calculatedAge = today.getFullYear() - birthDate.getFullYear();
        let m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            calculatedAge--;
        }
        finalAge = calculatedAge > 0 ? calculatedAge : 0;
    }
    if (ageInput && finalAge !== undefined && finalAge !== null) {
        ageInput.value = finalAge;
    }

    // MID handling
    if (midInput && p.member_id) {
        midInput.value = p.member_id;
    }

    // Aadhar handling: Combine adhar1, adhar2, adhar3
    let fullAadhar = '';
    if (p.adhar1 || p.adhar2 || p.adhar3) {
        fullAadhar = (p.adhar1 || '') + (p.adhar2 || '') + (p.adhar3 || '');
    } else if (p.aadhar_number || p.aadhar_card) {
        fullAadhar = p.aadhar_number || p.aadhar_card;
    }
    
    if (aadharInput && fullAadhar) {
        aadharInput.value = fullAadhar;
    }
    
    // Gender handling
    if (p.gender) {
        let g = p.gender.toLowerCase();
        let radio = container.querySelector(`input[type='radio'][value='${g}']`);
        if (radio) {
            radio.checked = true;
            let evt = new Event('change', { bubbles: true });
            radio.dispatchEvent(evt);
        }
    }
}

// ✅ Global: Head profile fetch (called inline oninput)
function fetchHeadProfile(phone) {
    showApiLoader();
    const nameInput   = document.getElementById('head_name');
    const fatherInput = document.getElementById('head_father_name');
    const step1Div    = document.getElementById('step-1');
    if (nameInput)   nameInput.value = '';
    if (fatherInput) fatherInput.value = '';

    $.ajax({
        url: 'https://apiv1.sadhumargi.com/api/fetch-profiles',
        method: 'POST',
        headers: { 'Authorization': 'Bearer vPW6doIdkAdf', 'Accept': 'application/json' },
        data: { mobile_number: phone },
        success: function (response) {
            if (response.profiles && response.profiles.length > 0) {
                if (response.profiles.length === 1) {
                    const p = response.profiles[0];
                    nameInput.value   = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
                    fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                    fillCityStateAanchal(p); // 📍 Auto-fill dropdowns
                    fillExtraFields(p, step1Div); // 📍 Auto-fill extra fields
                } else {
                    let optionsHtml = response.profiles.map((p, i) =>
                        `<option value="${i}">${p.first_name} ${p.last_name} — ${p.father_name || p.fathers_name || ''}</option>`
                    ).join('');
                    Swal.fire({
                        title: 'सदस्य चुनें',
                        html: `<select id="swal-profile-select" class="form-select mt-2">${optionsHtml}</select>`,
                        confirmButtonText: 'चुनें',
                        showCancelButton: true,
                        cancelButtonText: 'रद्द करें',
                        preConfirm: () => {
                            return document.getElementById('swal-profile-select').value;
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            const p = response.profiles[parseInt(result.value)];
                            nameInput.value   = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
                            fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                            fillCityStateAanchal(p); // 📍 Auto-fill dropdowns
                            fillExtraFields(p, step1Div); // 📍 Auto-fill extra fields
                        }
                    });
                }
            } else {
                Swal.fire({ icon:'info', title:'प्रोफ़ाइल नहीं मिली', text:'इस नंबर से कोई प्रोफ़ाइल नहीं मिली।', confirmButtonText:'ठीक है' });
            }
        },
        error: function () {
            Swal.fire({ icon:'warning', title:'API Error', text:'डाटा प्राप्त नहीं हो रहा।', confirmButtonText:'ठीक है' });
        },
        complete: function () {
            hideApiLoader();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
  
    const bookingForm = document.getElementById("bookingForm");
    const submitBtn = document.getElementById("submitBtn");
    const sixtyPlusOption = document.getElementById("60plus_option");
    const sixtyPlusMale = document.getElementById("60plus_male");
    const sixtyPlusFemale = document.getElementById("60plus_female");
    const totalMembersInput = document.querySelector("input[name='total_members']");
    const membersContainer = document.createElement("div");

    membersContainer.classList.add("mt-3");
    totalMembersInput.parentNode.appendChild(membersContainer);

  
   
        function addMemberFields(index) {
        if (document.querySelectorAll(".member-entry").length >= 3000) {
            Swal.fire("⚠ अधिकतम 3000 सदस्य ही हो सकते हैं!", "", "error");
            return;
        }

        const row = document.createElement("div");
        row.classList.add("row", "g-3", "member-entry", "mb-3", "border", "rounded", "p-2", "bg-light");

        row.innerHTML = `
            <div class="col-md-3">
                <label class="form-label fw-semibold">📱 मोबाइल नंबर <span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="text"
                           name="members[${index}][mobile_number]"
                           class="form-control phone-input member-mobile"
                           required
                           maxlength="10"
                           pattern="[0-9]{10}"
                           placeholder="10 अंक दर्ज करें"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text member-loader" style="display:none;">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </span>
                </div>
                <small class="text-muted">नंबर डालने के बाद Tab करें → नाम auto-fill होगा</small>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">👤 सदस्य का नाम <span style="color:red">*</span></label>
                <input type="text"
                       name="members[${index}][name]"
                       class="form-control member-name name-field"
                       required
                       placeholder="API से auto-fill या manually लिखें"
                       oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">👨 पिता/पति का नाम</label>
                <input type="text"
                       name="members[${index}][father_name]"
                       class="form-control member-father name-field"
                       placeholder="पिता / पति का नाम"
                       oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">🪪 आधार नंबर</label>
                <input type="text"
                       name="members[${index}][aadhar_number]"
                       class="form-control"
                       maxlength="12"
                       placeholder="12 अंकों का आधार"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div class="col-md-12 d-flex align-items-center gap-2 mt-1">
                <button type="button" class="btn btn-danger btn-sm remove-member">❌ हटाएं</button>
                <button type="button" class="btn btn-success btn-sm add-member">➕ और जोड़ें</button>
            </div>
        `;

        membersContainer.appendChild(row);

        const mobileInput = row.querySelector(".member-mobile");
        const nameInput   = row.querySelector(".member-name");
        const fatherInput = row.querySelector(".member-father");
        const loaderSpan  = row.querySelector(".member-loader");

        // ✅ On input: fetch instantly when 10 digits typed
        mobileInput.addEventListener("input", function () {
            const phone = this.value.trim();
            nameInput.value   = "";
            fatherInput.value = "";
            if (phone.length !== 10) return;

        showApiLoader(); // 🔒 Screen freeze

            $.ajax({
                url: "https://apiv1.sadhumargi.com/api/fetch-profiles",
                method: "POST",
                headers: {
                    "Authorization": "Bearer vPW6doIdkAdf",
                    "Accept": "application/json"
                },
                data: { mobile_number: phone },
                success: function (response) {
                    if (response.profiles && response.profiles.length > 0) {
                        if (response.profiles.length === 1) {
                            // ✅ Single profile → direct fill
                            const p = response.profiles[0];
                            nameInput.value   = ((p.first_name || '') + ' ' + (p.last_name || '')).trim().toUpperCase();
                            fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                            fillExtraFields(p, row); // 📍 Auto-fill age, gender, mid, aadhar
                        } else {
                            // ✅ Multiple profiles → SweetAlert selection
                            let optionsHtml = response.profiles.map((p, i) =>
                                `<option value="${i}">${p.first_name} ${p.last_name} — ${p.father_name || p.fathers_name || ''}</option>`
                            ).join('');
                            Swal.fire({
                                title: 'सदस्य चुनें',
                                html: `<select id="swal-member-select" class="form-select mt-2">${optionsHtml}</select>`,
                                confirmButtonText: 'चुनें',
                                showCancelButton: true,
                                cancelButtonText: 'रद्द करें',
                                preConfirm: () => document.getElementById('swal-member-select').value
                            }).then(result => {
                                if (result.isConfirmed) {
                                    const p = response.profiles[parseInt(result.value)];
                                    nameInput.value   = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
                                    fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                                    fillExtraFields(p, row); // 📍 Auto-fill age, gender, mid, aadhar
                                }
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: "info", title: "प्रोफ़ाइल नहीं मिली",
                            text: "इस नंबर से प्रोफ़ाइल नहीं मिली। कृपया नाम manually भरें।",
                            confirmButtonText: "ठीक है"
                        });
                        nameInput.focus();
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "warning", title: "API Error",
                        text: "डाटा प्राप्त नहीं हो रहा। कृपया नाम manually भरें।",
                        confirmButtonText: "ठीक है"
                    });
                    nameInput.focus();
                },
                complete: function () {
                    hideApiLoader(); // 🔓 Screen unfreeze
                }
            });
        });

        row.querySelector(".remove-member").addEventListener("click", function () {
            row.remove();
            totalMembersInput.value = document.querySelectorAll(".member-entry").length;
        });

        row.querySelector(".add-member").addEventListener("click", function () {
            addMemberFields(document.querySelectorAll(".member-entry").length + 1);
            totalMembersInput.value = document.querySelectorAll(".member-entry").length;
        });
    }


   totalMembersInput.addEventListener("blur", function () {
    let totalMembers = parseInt(totalMembersInput.value) || 0;

    membersContainer.innerHTML = "";

    if (totalMembers < 11 || totalMembers > 3000) {
        Swal.fire("⚠ Group Booking के लिए सदस्य संख्या कम से कम 11 होनी चाहिए!", "", "error");
        totalMembersInput.value = "";
        return;
    }

    // ✅ Ask user: Do you want to enter member details?
    Swal.fire({
        title: "क्या आप सदस्य की जानकारी भरना चाहते हैं?",
        text: `आपने ${totalMembers} सदस्य दर्ज किए हैं।`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "हाँ, भरना है",
        cancelButtonText: "नहीं",
    }).then((result) => {
        if (result.isConfirmed) {
            // ✅ Generate member fields only if user says YES
            for (let i = 1; i <= totalMembers; i++) {
                addMemberFields(i);
            }
        } else {
            // ❌ Reset value or leave it, just don’t generate fields
            membersContainer.innerHTML = "";
        }
    });
});


    sixtyPlusOption.addEventListener("change", function () {
        const showFields = this.value === "1";

        if (showFields) {
            sixtyPlusMale.classList.remove("d-none");
            sixtyPlusFemale.classList.remove("d-none");
        } else {
            sixtyPlusMale.classList.add("d-none");
            sixtyPlusFemale.classList.add("d-none");
        }
    });

    function validateNameInput(event) {
        event.target.value = event.target.value.replace(/[^a-zA-Z\s]/g, '').toUpperCase();
    }

    function validateNumberInput(event) {
        event.target.value = event.target.value.replace(/\D/g, '');
    }

    document.querySelector("input[name='name']").addEventListener("input", validateNameInput);
    document.querySelector("input[name='phone']").addEventListener("input", validateNumberInput);

 submitBtn.addEventListener("click", function (event) {
    event.preventDefault();

    let phone = document.querySelector("input[name='phone']").value.trim();
    let checkInDate = document.querySelector("input[name='check_in_date']").value;
    let checkOutDate = document.querySelector("input[name='check_out_date']").value;
    let name = document.querySelector("input[name='name']").value.trim();
    let today = new Date().toISOString().split("T")[0];
    let childCount = document.querySelector("input[name='child_count']").value;

    // ✅ Validate main fields
    if (!name) {
        Swal.fire("⚠ त्रुटि", "कृपया नाम दर्ज करें!", "error");
        return;
    }
    if (!/^\d{10}$/.test(phone)) {
        Swal.fire("⚠ त्रुटि", "मोबाइल नंबर 10 अंकों का होना चाहिए!", "error");
        return;
    }
    if (!checkInDate || !checkOutDate || checkInDate < today || checkOutDate < today) {
        Swal.fire("⚠ त्रुटि", "चेक-इन और चेक-आउट की तारीखें आज या भविष्य की होनी चाहिए!", "error");
        return;
    }
    if (childCount === '' || isNaN(childCount)) {
        Swal.fire("⚠ त्रुटि", "बच्चों की संख्या दर्ज करें!", "error");
        return;
    }

    let totalMale = document.querySelector("input[name='total_male']").value;
    let totalFemale = document.querySelector("input[name='total_female']").value;
    
    if (totalMale === '' || isNaN(totalMale)) {
        Swal.fire("⚠ त्रुटि", "कुल पुरुषों की संख्या दर्ज करें!", "error");
        return;
    }
    if (totalFemale === '' || isNaN(totalFemale)) {
        Swal.fire("⚠ त्रुटि", "कुल महिलाओं की संख्या दर्ज करें!", "error");
        return;
    }

    // ✅ Ensure numeric fields are converted to integers
    document.querySelector("input[name='total_male']").value = parseInt(totalMale) || 0;
    document.querySelector("input[name='total_female']").value = parseInt(totalFemale) || 0;
    document.querySelector("input[name='child_count']").value = parseInt(childCount) || 0;

    // ✅ Validate members only if they exist
    let memberEntries = document.querySelectorAll(".member-entry");

    if (memberEntries.length > 0) {
        let allValid = true;
        let errorMessage = "";

        memberEntries.forEach((entry, index) => {
            const name = entry.querySelector("input[name$='[name]']")?.value.trim();
            const mobileNumber = entry.querySelector("input[name$='[mobile_number]']")?.value.trim();

            if (!name || !mobileNumber) {
                allValid = false;
                errorMessage = `सदस्य ${index + 1} का नाम और मोबाइल नंबर भरें।`;
            }
        });

        if (!allValid) {
            Swal.fire("⚠ त्रुटि", errorMessage, "error");
            return;
        }
    }


    // ✅ Confirm and submit
    Swal.fire({
        title: "क्या आप सुनिश्चित हैं?",
        text: "क्या आप बुकिंग सबमिट करना चाहते हैं?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "हां, सबमिट करें!"
    }).then((result) => {
        if (result.isConfirmed) {
            bookingForm.submit();
        }
    });
});


const checkInInput = document.querySelector("input[name='check_in_date']");
const checkoutInput = document.querySelector("input[name='check_out_date']");
const checkInTimeInput = document.querySelector("input[name='check_in_time']");
const checkOutTimeInput = document.querySelector("input[name='check_out_time']");

const formatDate = (date) => {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
};

checkInInput.addEventListener("change", function () {
    const checkInDate = new Date(this.value);
    if (isNaN(checkInDate)) return;

    // Update checkout min to match check-in date
    checkoutInput.min = formatDate(checkInDate);

    // Clear any previously selected invalid check-out date
    if (checkoutInput.value) {
        const currentCheckout = new Date(checkoutInput.value);
        if (currentCheckout < checkInDate) {
            checkoutInput.value = '';
        }
    }
});

checkoutInput.addEventListener("change", function () {
    const checkoutDate = new Date(this.value);
    const checkInDate = new Date(checkInInput.value);
    
    if (isNaN(checkoutDate)) return;

    // Validate checkout is not before check-in
    if (checkoutDate < checkInDate) {
        alert("Check-out date cannot be before check-in date");
        this.value = '';
        return;
    }

    // If checkout is on same day, ensure it's not before check-in time
    if (checkoutDate.toDateString() === checkInDate.toDateString()) {
        checkOutTimeInput.min = checkInTimeInput.value || "00:00";
    }
});

checkInTimeInput.addEventListener("change", function () {
    const timeValue = this.value;
    const checkInDate = new Date(checkInInput.value);

    // Update checkout time minimum if same day
    const checkoutDate = new Date(checkOutTimeInput.value ? checkoutInput.value : '');
    if (checkoutDate.toDateString && checkoutDate.toDateString() === checkInDate.toDateString()) {
        checkOutTimeInput.min = timeValue;
    }
});

checkOutTimeInput.addEventListener("change", function () {
    const checkInDate = new Date(checkInInput.value);
    const checkoutDate = new Date(checkoutInput.value);
    const checkInTime = checkInTimeInput.value;
    const checkoutTime = this.value;

    // If same day, checkout time must be after check-in time
    if (checkoutDate.toDateString() === checkInDate.toDateString()) {
        if (checkoutTime <= checkInTime) {
            alert("Check-out time must be after check-in time on the same day");
            this.value = '';
            return;
        }
    }
});


    //---------Api Backend----------------
            let citiesData = [];

            // 🌐 Fetch all city/state/anchal data from single API
            $("#cityLoader").show();
            $.get("https://mrm.sadhumargi.org/api/cities", function(response) {
                if (response.success && response.cities.length) {
                    citiesData = response.cities;

                    // 🏙️ Populate Cities
                    let citySelect = $("select[name='city']");
                    citySelect.empty().append('<option value="">शहर चुनें</option>');
                    response.cities.forEach(function(city) {
                        citySelect.append(`<option value="${city.city_id}">${city.city_name}</option>`);
                    });
                    citySelect.select2({
                        theme: 'bootstrap4',
                        placeholder: "शहर का नाम टाइप करें...",
                        allowClear: true,
                        width: '100%',
                        minimumInputLength: 0
                    });

                    // Auto-open Select2 on click/focus so user can type directly
                    citySelect.on('select2:open', function() {
                        document.querySelector('.select2-search__field') && document.querySelector('.select2-search__field').focus();
                    });
                    $(document).on('click', 'select[name="city"] + .select2-container .select2-selection', function() {
                        $("select[name='city']").select2('open');
                    });

                    // 🏞️ Populate unique States
                    let stateMap = {};
                    response.cities.forEach(function(city) {
                        stateMap[city.state_id] = city.state_name;
                    });
                    let stateSelect = $("select[name='state']");
                    stateSelect.empty().append('<option value="">राज्य चुनें</option>');
                    Object.entries(stateMap).sort((a, b) => a[1].localeCompare(b[1])).forEach(function([id, name]) {
                        stateSelect.append(`<option value="${id}">${name}</option>`);
                    });
                    stateSelect.select2({
                        theme: 'bootstrap4',
                        placeholder: "राज्य चुनें",
                        allowClear: true,
                        width: '100%'
                    });

                    // 🌐 Populate unique Anchals
                    let anchalMap = {};
                    response.cities.forEach(function(city) {
                        anchalMap[city.anchal_id] = city.anchal_name;
                    });
                    let anchalSelect = $("select[name='aanchal']");
                    anchalSelect.empty().append('<option value="">अंचल चुनें</option>');
                    Object.entries(anchalMap).sort((a, b) => a[1].localeCompare(b[1])).forEach(function([id, name]) {
                        anchalSelect.append(`<option value="${id}">${name}</option>`);
                    });
                    anchalSelect.select2({
                        theme: 'bootstrap4',
                        placeholder: "अंचल चुनें",
                        allowClear: true,
                        width: '100%'
                    });
                }
                $("#cityLoader").hide();
            }).fail(function() {
                $("#cityLoader").hide();
                Swal.fire("Error!", "शहर की जानकारी लोड करने में त्रुटि हुई।", "error");
            });


// ✅ Allow only English letters on input
$(document).on('input', '.name-field', function () {
    let cleanVal = $(this).val().replace(/[^A-Za-z .'-]/g, '');
    $(this).val(cleanVal);
});

// ❌ On blur: if value contains any non-English char, clear it
$(document).on('blur', '.name-field', function () {
    let val = $(this).val();
    // Check for invalid (non-ASCII)
    if (/[^A-Za-z .'-]/.test(val)) {
        $(this).val(''); // Clear field
        Swal.fire({
            icon: 'warning',
            title: '⚠️ Invalid Characters',
            text: 'Name field में केवल English characters (A-Z, a-z) ही allowed हैं।',
            confirmButtonText: 'ठीक है'
        });
    }
});



   $(document).ready(function() {
        // Restrict numbers from being entered in the Father Name field
        $('#father_name').on('input', function() {
            var inputValue = $(this).val();
            // Replace any number with an empty string
            $(this).val(inputValue.replace(/[0-9]/g, ''));
        });
    });


            // 🌆 When City is selected - auto-fill state & anchal from cached data
            $("select[name='city']").change(function() {
                const cityId = $(this).val();

                if (cityId) {
                    const city = citiesData.find(c => c.city_id == cityId);
                    if (city) {
                        $("select[name='state']").val(city.state_id).trigger("change.select2");
                        $("select[name='aanchal']").val(city.anchal_id).trigger("change.select2");
                    }
                } else {
                    $("select[name='state']").val('').trigger('change.select2');
                    $("select[name='aanchal']").val('').trigger('change.select2');
                }
            });

            // 🔍 Auto-focus Select2 search box on open so user can type directly
            $(document).on('select2:open', function() {
                setTimeout(function() {
                    var searchInput = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchInput) searchInput.focus();
                }, 0);
            });



       let isAlertOpen = false;
$(document).ready(function () {
  $(document).on('blur', 'input[name="aadhar_number"], input[name^="members"][name$="[aadhar_number]"]', function () {
    var aadharInput = $(this);
    var aadharNumber = aadharInput.val().trim();

    // Step 1: Check if 12 digits and numeric only
    if (!/^\d{12}$/.test(aadharNumber)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Aadhar Number',
            text: 'Aadhar number must be exactly 12 digits.',
        });
        aadharInput.val('');
        return;
    }

    // Step 2: Check for duplicates within the form
    var isDuplicate = false;
    $('input[name="aadhar_number"], input[name^="members"][name$="[aadhar_number]"]').not(this).each(function () {
        if ($(this).val().trim() === aadharNumber) {
            isDuplicate = true;
            return false; // break
        }
    });

    if (isDuplicate) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Aadhar in Form',
            text: 'This Aadhar number is already entered in the form.',
        });
        aadharInput.val('');
        return;
    }

    // Step 3: Check with backend if Aadhaar already exists
    $.ajax({
        url: '/check-aadhar',
        method: 'GET',
        data: { aadhar_number: aadharNumber },
        success: function (response) {
            if (response.exists) {
                Swal.fire({
                    icon: 'error',
                    title: 'Aadhar Already Registered',
                    text: 'This Aadhar number is already registered in the database.',
                });
                aadharInput.val('');
            }
        },
        error: function () {
            // Optional: if server fails
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Unable to check Aadhar number. Please try again.',
            });
        }
    });
});

});

 });
</script>

<script>
    function showStep(step) {
        document.querySelectorAll('.form-step').forEach(function(el) {
            el.classList.remove('active');
        });
        document.querySelectorAll('.step-indicator .step').forEach(function(el, index) {
            if (index < step) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
            if (index < step - 1) {
                el.classList.add('completed');
            } else {
                el.classList.remove('completed');
            }
        });
        document.getElementById('step-' + step).classList.add('active');
    }

    function nextStep(currentStep) {
        const currentContainer = document.getElementById('step-' + currentStep);
        const inputs = currentContainer.querySelectorAll('input, select, textarea');
        let valid = true;
        
        // Custom validity for dynamically added elements and regular inputs
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].hasAttribute('required') && !inputs[i].value) {
                inputs[i].reportValidity();
                valid = false;
                break;
            }
            if (!inputs[i].checkValidity()) {
                inputs[i].reportValidity();
                valid = false;
                break;
            }
        }
        
        if (valid) {
            showStep(currentStep + 1);
        }
    }

    function prevStep(currentStep) {
        showStep(currentStep - 1);
    }
</script>





</body>
</html>  
