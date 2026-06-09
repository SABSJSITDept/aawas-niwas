<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Family Booking</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
    <!-- jQuery -->
      <!-- Select2 CSS -->

    <!-- Optional Bootstrap 5 Theme for Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    
<style> 
.spinner-border {
    display: inline-block;
    width: 3rem;
    height: 3rem;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
}

@keyframes spinner-border {
    100% {
        transform: rotate(360deg);
    }
}
</style>

    <style>
    /* Hide default radio button */
    .form-check-input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        background-color: #fff;
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 1.15em;
        height: 1.15em;
        border: 2px solid #dc3545; /* 🔴 Red border */
        border-radius: 50%;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: border 0.2s ease-in-out;
    }

    .form-check-input[type="radio"]::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        border-radius: 50%;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em #dc3545; /* 🔴 Red inner dot */
    }

    .form-check-input[type="radio"]:checked::before {
        transform: scale(1);
    }

    .form-check-input[type="radio"]:checked {
        border-color: #dc3545; /* 🔴 Red outer ring when checked */
    }

    /* Tailwind CSS overrides */
    body {
        background-color: #f3f4f6;
    }


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

<style>
/* ✅ Full-screen API Loading Overlay */
#apiLoadingOverlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    z-index: 99999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 16px;
}
#apiLoadingOverlay .overlay-box {
    background: white;
    border-radius: 16px;
    padding: 32px 48px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: overlayPop 0.2s ease;
}
#apiLoadingOverlay .overlay-box .spinner-border {
    width: 3rem; height: 3rem;
    border-width: 4px;
    color: #2575fc;
}
#apiLoadingOverlay .overlay-box p {
    margin: 12px 0 0;
    font-weight: 600;
    font-size: 1rem;
    color: #374151;
    font-family: 'Poppins', sans-serif;
}
@keyframes overlayPop {
    from { transform: scale(0.85); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
</style>

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
</head>
<body>

<!-- ✅ Full-Screen API Loading Overlay -->
<div id="apiLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.55); backdrop-filter:blur(3px); z-index:99999; justify-content:center; align-items:center; flex-direction:column;">
    <div style="background:white; border-radius:16px; padding:32px 48px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div class="spinner-border" style="width:3rem;height:3rem;border-width:4px;color:#2575fc;" role="status"></div>
        <p style="margin:14px 0 0; font-weight:600; font-size:1rem; color:#374151; font-family:'Poppins',sans-serif;">🔄 डेटा लोड हो रहा है...<br><small style="font-weight:400;color:#6b7280;">कृपया प्रतीक्षा करें</small></p>
    </div>
</div>

<nav class="navbar navbar-expand-lg shadow-md bg-white" style="z-index: 1050; border-bottom: 3px solid #f59e0b;">
  <div class="container-fluid px-4 d-flex justify-content-between align-items-center py-2">
    
    <!-- Left Side: Logo + Title -->
    <a class="navbar-brand d-flex align-items-center gap-3 text-gray-800 text-decoration-none" href="#">
      <img src="{{ asset('images/chaturmaslogo.png') }}" alt="Logo" width="55" height="55" class="rounded-circle shadow-sm border border-gray-200 p-1">
      <div class="d-flex flex-column lh-sm">
        <span class="fw-bold fs-4 text-indigo-700">समर्पण महोत्सव - 2026</span>
        <small class="fs-6 text-gray-500 font-medium">बीकानेर, राजस्थान</small>
      </div>
    </a>

    <!-- Right Side: Home Button -->
    <a href="{{ route('home') }}" class="btn btn-outline-indigo px-4 py-2 font-semibold rounded-lg shadow-sm transition hover:bg-indigo-50 border border-indigo-600 text-indigo-700 d-flex align-items-center gap-2">
      <i class="bi bi-house-door-fill"></i> Home
    </a>
    
  </div>
</nav>



<!-- Gap between navbar and banner -->
<div class="mb-4"></div>

<!-- Header Banner + Contact Info inside a Stylish Card -->
     <section class="container px-3 px-md-4 my-4">
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
                    <div class="text-sm mb-2 flex justify-between"><span class="font-semibold text-gray-700">संपर्क 2:</span> <a href="tel:+919876543211" class="text-blue-600 hover:underline">+91 9876543211</a></div>
                    <div class="text-sm flex justify-between"><span class="font-semibold text-gray-700">कार्यालय:</span> <a href="tel:+919876543212" class="text-blue-600 hover:underline">+91 9876543212</a></div>
                </div>
            </div>
        </div>
   </section>

<!-- Main Content -->
<div class="container px-3 px-md-4 mb-5">


<!-- Manual Entry Form -->
<div id="manual-tab" class="tab-content active">
<form id="bookingForm" method="POST" action="{{ route('family-booking.store') }}">
            @csrf
            
            <!-- Stepper UI -->
            <div class="step-indicator mb-4">
                <div class="step active" id="indicator-1">1</div>
                <div class="step" id="indicator-2">2</div>
                <div class="step" id="indicator-3">3</div>
            </div>

            <div class="form-step active" id="step-1">
                <h4 class="text-primary mb-3">व्यक्तिगत जानकारी (Personal Details)</h4>
            <div class="row g-2">

                <!-- 📱 Mobile FIRST -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold"><span style="color: red;">*</span> मोबाइल नंबर</label>
                    <div class="input-group">
                        <input type="text" name="phone" id="family_head_phone" class="form-control" maxlength="10"
                               placeholder="10 अंक दर्ज करें"
                               oninput="this.value=this.value.replace(/[^0-9]/g,''); if(this.value.length===10) fetchFamilyHeadProfile(this.value);"
                               required>
                        <span class="input-group-text" id="familyHeadLoader" style="display:none;">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        </span>
                    </div>
                    <small class="text-muted">10 अंक डालते ही डेटा auto-fill होगा</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">  <span style="color: red;">*</span> नाम </label>  
                    <input type="text" name="name" id="family_head_name" class="form-control" placeholder="API से auto-fill या manually लिखें" required>
                </div>
            
                <div class="col-md-3">
                    <label class="form-label fw-semibold">  <span style="color: red;">*</span> पिता/पति का नाम </label>  
                    <input type="text" name="father_name" id="family_head_father" class="form-control"  required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">  <span style="color: red;">*</span> उम्र</label>
                    <input type="text" name="age" class="form-control"   required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold"><span style="color: red;">*</span> लिंग (Gender)</label>
                    <div class="border p-2 rounded d-flex gap-3" >
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="male" id="genderMale" required>
                            <label class="form-check-label" for="genderMale">पुरुष</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="female" id="genderFemale">
                            <label class="form-check-label" for="genderFemale">महिला</label>
                        </div>
                    </div>
                </div>

<div class="col-md-3 mb-3">
    <label class="form-label" style="color: red;">MID</label>
    <input type="text" name="mid" class="form-control" id="mid" maxlength="12" >
</div>


                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">आधार कार्ड नंबर</label>
                      <input type="text" name="aadhar_number" class="form-control aadhar-check" maxlength="12" >
                    </div>

              <div class="col-md-3 mb-3">
                            <label class="form-label  fw-semibold">शहर <span style="color: red;">*</span> </label>
                            <div class="d-flex align-items-center">
                                <select name="city" class="form-select"></select>
                                <div id="cityLoader" class="loader" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label  fw-semibold">राज्य <span style="color: red;">*</span> </label>
                            <div class="d-flex align-items-center">
                                <select name="state" class="form-select"></select>
                                <div id="stateLoader" class="loader" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label  fw-semibold">अंचल <span style="color: red;">*</span></label>
                            <div class="d-flex align-items-center">
                                <select name="aanchal" class="form-select"></select>
                                <div id="aanchalLoader" class="loader" style="display: none;"></div>
                            </div>
                        </div>
<div class="col-md-3 mb-3">
    <label class="form-label fw-semibold">क्या आप वीर परिवार से हैं? <span style="color: red;">*</span></label>
    <div class="d-flex align-items-center gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_veer_parivar" value="1" id="veerYes">
            <label class="form-check-label" for="veerYes">हाँ</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_veer_parivar" value="0" id="veerNo" checked>
            <label class="form-check-label" for="veerNo">नहीं</label>
        </div>
    </div>
</div>

<div class="col-md-3 mb-3" id="veer-relation-group" style="display: none;">
    <label class="form-label fw-semibold">रिश्ता <span style="color: red;">*</span></label>
    <select name="veer_relation" class="form-select">
        <option value="">-- चुनें --</option>
        <option value="Mother">माता</option>
        <option value="Father">पिता</option>
        <option value="Son">पुत्र</option>
        <option value="Daughter">पुत्री (विवाहित/अविवाहित)</option>
        <option value="brother">भाई</option>
        <option value="Sister"> बहन (विवाहित/अविवाहित)</option>
        <option value="Husband">पति</option>
        <option value="wife">पत्नि</option>
    </select>
</div>

<div class="col-md-3 mb-3" id="ms-name-group" style="display: none;">
    <label class="form-label fw-semibold">परिवार से दीक्षित म.सा.का नाम
 <span style="color: red;">*</span></label>
    <input type="text" name="ms_name" class="form-control" placeholder="परिवार से दीक्षित म.सा.का नाम
">
</div>

            </div> <!-- End Step 1 row -->
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-primary px-4 py-2" onclick="nextStep(1)">Next &rarr;</button>
            </div>
            </div> <!-- End Step 1 -->

            <div class="form-step" id="step-2">
                <h4 class="text-primary mb-3">सदस्यों की जानकारी (Member Details)</h4>
                <div class="row g-3">
    <div class="col-md-3    mb-3   ">
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>  क्या आपके साथ कोई सदस्य आ रहा है ?</label>
    <div class="border p-1 rounded">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="family_coming" value="1"  required>
            <label class="form-check-label">हाँ</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="family_coming" value="0"  required>
            <label class="form-check-label">नहीं </label>
        </div>
    </div>
</div>

<div class="col-md-3 mb-3" id="people_count" style="display: none;"> 
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>  आपके साथ आने वाले सदस्यों की संख्या  </label>
    <input type="number" name="no_of_people" class="form-control" id="no_of_people" min="0" max="9" value="0" readonly style="background-color: #e9ecef; cursor: not-allowed;" required>
    <small id="people_error" class="text-danger d-none">आप अधिकतम 9 सादस्य का ही पंजीकरण  यहा करवा सकते है! (आप Head सहित अधिकतम 10)</small>
    <small class="text-muted d-block">आप (Head) सहित अधिकतम 10 व्यक्ति</small>
</div>

<input type="hidden" name="total_persons" value="">

<div class="col-md-3 mb-3" id="children_count" style="display: none;"> 
    <label class="form-label fw-semibold">  
        <span style="color: red;">*</span>  बच्चो की संख्या 10 साल तक की  
    </label>
    <input type="number" name="no_of_children" class="form-control" id="no_of_children"   required>
</div>

<!-- Family Members Details -->
<div class="col-md-12 mb-3" id="addMemberButtonsDiv" style="display: none;">
    <label class="form-label fw-semibold d-block">सदस्य जोड़ें:</label>
    <button type="button" class="btn btn-info text-white shadow-sm fw-bold me-2" onclick="showFamilySelectionModal()">
        <i class="fas fa-users"></i> परिवार के सदस्यों को चुनें (API से)
    </button>
    <button type="button" class="btn btn-success shadow-sm fw-bold" onclick="addManualFamilyMember()">
        <i class="fas fa-user-plus"></i> मैनुअली सदस्य जोड़ें
    </button>
</div>
<div id="family_members_section"></div>

<div class="col-md-3 mb-3 family-dependent">
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>  कुल पुरुष <small class="text-muted">(Auto)</small></label>
    <input type="number" name="total_male" id="total_male" class="form-control" style="border: 2px solid #28a745; background-color: #eaffea; color: #155724; font-weight: bold; cursor: not-allowed; pointer-events: none;" readonly tabindex="-1">
</div>

<div class="col-md-3 mb-3 family-dependent">
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>  कुल महिला <small class="text-muted">(Auto)</small></label>
    <input type="number" name="total_female" id="total_female" class="form-control" style="border: 2px solid #dc3545; background-color: #fff0f0; color: #721c24; font-weight: bold; cursor: not-allowed; pointer-events: none;" readonly tabindex="-1">
</div>

<div class="col-md-4 mb-4 family-dependent">
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>  60 वर्ष से अधिक आयु का कोई व्यक्ति <small class="text-muted">(Auto)</small></label>
    <select id="sixty_plus_option" class="form-control" style="border: 2px solid #333; background-color: #eaffea; font-weight: bold; pointer-events: none; cursor: not-allowed;" tabindex="-1">
        <option value="no">नहीं </option>
        <option value="yes">हाँ</option>
    </select>
</div>

<div class="col-md-1 mb-6 family-dependent" id="sixty_plus_male">
    <label class="form-label fw-semibold"> संख्या पुरुष <small class="text-muted">(Auto)</small></label>
    <input type="number" name="sixty_plus_male" class="form-control" style="border: 2px solid #28a745; background-color: #eaffea; color: #155724; font-weight: bold; cursor: not-allowed; pointer-events: none;" readonly tabindex="-1">
</div>

<div class="col-md-1 mb-6 family-dependent" id="sixty_plus_female">
    <label class="form-label fw-semibold">संख्या  महिला <small class="text-muted">(Auto)</small></label>
    <input type="number" name="sixty_plus_female" class="form-control" style="border: 2px solid #dc3545; background-color: #fff0f0; color: #721c24; font-weight: bold; cursor: not-allowed; pointer-events: none;" readonly tabindex="-1">
</div>


            </div> <!-- End Step 2 row -->
            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-4 py-2" onclick="prevStep(2)">&larr; Previous</button>
                <button type="button" class="btn btn-primary px-4 py-2" onclick="nextStep(2)">Next &rarr;</button>
            </div>
            </div> <!-- End Step 2 -->

            <div class="form-step" id="step-3">
<div class="travel-details p-3 rounded shadow-sm bg-white"  border: 2px solid #007bff;>
    <h4 class="section-title text-center text-primary fw-bold mb-3">यात्रा विवरण (Travel Details)</h4>
    
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span> आने का वाहन</label>
            <select name="travel_type" class="form-select shadow-sm"  required >
                            <option value="Train">🚆 ट्रैन</option>      
            <option value="Flight">✈️ विमान</option>
                <option value="Bus">🚌 बस</option>
                <option value="Car">🚗 कार</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span> आगमन की दिनांक</label>
            <input type="date" name="check_in_date" class="form-control shadow-sm rounded"  required>
        </div>


        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span> आगमन का समय</label>
            <input type="time" name="check_in_time" class="form-control shadow-sm rounded"  required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>  प्रस्थान की दिनांक</label>
            <input type="date" name="check_out_date" class="form-control shadow-sm rounded"  required>
        </div>

        

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span> प्रस्थान का समय</label>
            <input type="time" name="check_out_time" class="form-control shadow-sm rounded"  required >
        </div>
    </div>

<div class="col-md-12 mb-3">
    <label class="form-label fw-semibold">रिमार्क (Remark)</label>
    <textarea name="remark" class="form-control" rows="3" maxlength="1000"  placeholder="यहाँ रिमार्क लिखें..."></textarea>
</div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="prevStep(3)">&larr; Previous</button>
                    <button type="button" id="submitButton" class="btn btn-success px-5 py-2 fw-bold shadow">
                    ✅ सबमिट करें
                    </button>
                </div>
       </div>
            </div> <!-- End Step 3 -->
        </form>
</div>
</div> <!-- Close manual-tab -->

</div> <!-- Close main container -->
<footer class="footer bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026  श्री साधुमार्गी जैन संघ | All Rights Reserved.</p>
    </div>
</footer>
   

<script>
// ✅ Helper: Show/Hide full-screen overlay
function showApiLoader() {
    const el = document.getElementById('apiLoadingOverlay');
    if (el) { el.style.display = 'flex'; }
}
function hideApiLoader() {
    const el = document.getElementById('apiLoadingOverlay');
    if (el) { el.style.display = 'none'; }
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

// ✅ Global: Family Head profile fetch (called inline oninput)
window.apiFamilyProfiles = []; // 📍 Store profiles globally

function fetchFamilyHeadProfile(phone) {
    showApiLoader();
    const nameInput   = document.getElementById('family_head_name');
    const fatherInput = document.getElementById('family_head_father');
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
                window.apiFamilyProfiles = response.profiles; // 📍 Save for later selection
                
                if (response.profiles.length === 1) {
                    const p = response.profiles[0];
                    nameInput.value   = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
                    fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                    fillCityStateAanchal(p); // 📍 Auto-fill dropdowns
                    fillExtraFields(p, step1Div); // 📍 Auto-fill age, gender, mid, aadhar
                } else {
                    let optionsHtml = response.profiles.map((p, i) =>
                        `<option value="${i}">${p.first_name} ${p.last_name} — ${p.father_name || p.fathers_name || ''}</option>`
                    ).join('');
                    Swal.fire({
                        title: 'सदस्य चुनें',
                        html: `<select id="swal-fhead-select" class="form-select mt-2">${optionsHtml}</select>`,
                        confirmButtonText: 'चुनें',
                        showCancelButton: true,
                        cancelButtonText: 'रद्द करें',
                        preConfirm: () => document.getElementById('swal-fhead-select').value
                    }).then(result => {
                        if (result.isConfirmed) {
                            const p = response.profiles[parseInt(result.value)];
                            nameInput.value   = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
                            fatherInput.value = (p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase();
                            fillCityStateAanchal(p); // 📍 Auto-fill dropdowns
                            fillExtraFields(p, step1Div); // 📍 Auto-fill age, gender, mid, aadhar
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

// ✅ Show multiple member selection
function showFamilySelectionModal() {
    if (!window.apiFamilyProfiles || window.apiFamilyProfiles.length <= 1) {
        Swal.fire({icon: 'info', title: 'कोई अन्य सदस्य नहीं', text: 'इस मोबाइल नंबर पर अन्य सदस्य उपलब्ध नहीं हैं।'});
        return;
    }

    let headPhone = document.getElementById('family_head_phone').value.trim();
    let headName = document.getElementById('family_head_name').value.trim().toUpperCase();

    let optionsHtml = '<div class="text-start" style="max-height:300px; overflow-y:auto;">';
    window.apiFamilyProfiles.forEach((p, i) => {
        let fullName = ((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase();
        let isHead = (fullName === headName);
        let disabledAttr = isHead ? 'disabled' : '';
        let badge = isHead ? '<span class="badge bg-secondary ms-2">Head</span>' : '';

        optionsHtml += `
        <div class="form-check border-bottom py-2">
            <input class="form-check-input swal-family-checkbox" type="checkbox" value="${i}" id="fprofile_${i}" ${disabledAttr}>
            <label class="form-check-label fw-semibold" style="cursor:pointer;" for="fprofile_${i}">
                ${fullName} <small class="text-muted d-block">${p.father_name || p.fathers_name || ''}</small>
            </label>
            ${badge}
        </div>`;
    });
    optionsHtml += '</div>';

    Swal.fire({
        title: 'परिवार के सदस्य चुनें',
        html: optionsHtml,
        confirmButtonText: 'जोड़ें',
        showCancelButton: true,
        cancelButtonText: 'रद्द करें',
        preConfirm: () => {
            return Array.from(document.querySelectorAll('.swal-family-checkbox:checked')).map(cb => parseInt(cb.value));
        }
    }).then(result => {
        if (result.isConfirmed && result.value.length > 0) {
            let selectedProfiles = result.value.map(i => window.apiFamilyProfiles[i]);
            
            let currentCount = $(".family-member").length;
            let newTotal = currentCount + selectedProfiles.length;
            
            if (newTotal > 9) {
                Swal.fire({icon: 'warning', title: 'Limit Exceeded', text: 'आप (Head) सहित अधिकतम 10 व्यक्ति ही हो सकते हैं।'});
                return;
            }

            // Loop to append new rows securely
            selectedProfiles.forEach((p, idx) => {
                let rowIdx = currentCount + idx + 1;
                addFamilyMember(rowIdx); // Ensure this function is globally accessible!

                let memberDiv = $(`.family-member[data-index='${rowIdx}']`);
                let mobileInput = memberDiv.find('.family-mobile-input')[0];
                let nameInput   = memberDiv.find('.family-name-field')[0];
                let fatherInput = memberDiv.find('.family-father-field')[0];
                
                $(mobileInput).val(p.mobile_number || headPhone);
                $(nameInput).val(((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase());
                $(fatherInput).val((p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase());
                fillExtraFields(p, memberDiv[0]); // 📍 Auto-fill extra fields like age, gender, aadhar
            });
            
            $("#no_of_people").val(newTotal);
            $("#no_of_people").val(newTotal);
            if (typeof window.updateGenderCount === 'function') window.updateGenderCount();
            if (typeof window.updatePeopleCount === 'function') window.updatePeopleCount();
        }
    });
}

// ✅ Add single member manually via explicitly provided button
window.addManualFamilyMember = function() {
    if (typeof window.addFamilyMember !== 'function') return;
    let currentCount = $(".family-member").length;
    if (currentCount >= 9) {
        Swal.fire({icon: 'warning', title: 'Limit Exceeded', text: 'आप (Head) सहित अधिकतम 10 व्यक्ति ही हो सकते हैं।'});
        return;
    }
    let rowIdx = currentCount + 1;
    window.addFamilyMember(rowIdx);
    if (typeof window.updateGenderCount === 'function') window.updateGenderCount();
    if (typeof window.updatePeopleCount === 'function') window.updatePeopleCount();
};
</script>

<script>

   
$(document).ready(function () {

    // Initialize cached family data
    let cachedFamilyData = {
        no_of_people: "",
        no_of_children: "",
        total_male: "",
        total_female: "",
        sixty_plus_option: "",
        sixty_plus_male: "",
        sixty_plus_female: "",
        members: [] 
    };

    let peopleCount = $("#people_count");
    let totalMale = $("input[name='total_male']").closest(".family-dependent");
    let totalFemale = $("input[name='total_female']").closest(".family-dependent");
    let sixtyPlusOption = $("#sixty_plus_option").closest(".family-dependent");
    let sixtyPlusMale = $("#sixty_plus_male").closest(".family-dependent");
    let sixtyPlusFemale = $("#sixty_plus_female").closest(".family-dependent");
    let noOfPeopleInput = $("input[name='no_of_people']");
    let familyMembersSection = $("#family_members_section");
    const nameRegex = "^[\\p{L} .'-]+$";       
    const aadharRegex = "^[0-9]{12}$"; 

    $('input[name="family_coming"]').on('change', function () {
        if ($(this).val() === '1') {
            $('#children_count').show();
            $('#people_count').show();
            $('#addMemberButtonsDiv').show();
            $('#no_of_people').val($(".family-member").length);
        } else {
            $('#children_count').hide();
            $('#people_count').hide();
            $('#addMemberButtonsDiv').hide();
            $('#no_of_children').val(''); // Clear value
            familyMembersSection.html("");
            if (typeof window.updatePeopleCount === 'function') window.updatePeopleCount();
            if (typeof window.updateGenderCount === 'function') window.updateGenderCount();
        }
    });

 //---------Api Backend----------------
            const apiBase = "https://apiv1.sadhumargi.com/api";
            const token = "vPW6doIdkAdf"; // <- 🛡️ Replace with actual Bearer Token
            let allStates = [];
            let allAnchals = [];
            $.ajaxSetup({
                headers: {
                    "Authorization": `Bearer ${token}`
                    , "Accept": "application/json"
                }
            });

            // 🏙️ Get Cities
            $("#cityLoader").show();
            $.get(`${apiBase}/get_cities_all`, function(response) {
                if (response.status) {
                    let citySelect = $("select[name='city']");
                    citySelect.empty().append('<option value="">Select City</option>');
                    response.anchals.forEach(function(city) {
                        citySelect.append(`<option value="${city.city_id}">${city.city_name}</option>`);
                    });
                }
                $("#cityLoader").hide();
            });

            // 🏞️ States
            $("#stateLoader").show();
            $.get(`${apiBase}/states`, function(response) {
                if (response.status) {
                    allStates = response.states; // 💾 save for reuse
                    let stateSelect = $("select[name='state']");
                    stateSelect.empty().append('<option value="">Select State</option>');
                    allStates.forEach(function(state) {
                        stateSelect.append(`<option value="${state.state_id}">${state.state_name}</option>`);
                    });

                    stateSelect.select2({
                        theme: 'bootstrap4'
                        , placeholder: "Select an option"
                        , allowClear: true
                        , width: '100%'
                    });
                }
                $("#stateLoader").hide();
            });

            // 🌐 Anchals
            $("#aanchalLoader").show();
            $.get(`${apiBase}/anchals`, function(response) {
                if (response.status) {
                    allAnchals = response.anchals; // 💾 save for reuse
                    let anchalSelect = $("select[name='aanchal']");
                    anchalSelect.empty().append('<option value="">Select Aanchal</option>');
                    allAnchals.forEach(function(anchal) {
                        anchalSelect.append(`<option value="${anchal.anchal_id}">${anchal.name}</option>`);
                    });

                    anchalSelect.select2({
                        theme: 'bootstrap4'
                        , placeholder: "Select an option"
                        , allowClear: true
                        , width: '100%'
                    });
                }
                $("#aanchalLoader").hide();
            });



            // 🌆 When City is selected
            $("select[name='city']").change(function() {
                const cityId = $(this).val();

                if (cityId) {
                    $.get(`${apiBase}/location-details/${cityId}`, function(response) {
                        if (response.status) {
                            const stateId = response.data.state.state_id;
                            const anchalId = response.data.anchal.anchal_id;

                            // ✅ Ensure options exist in dropdown before setting
                            const $stateSelect = $("select[name='state']");
                            const $aanchalSelect = $("select[name='aanchal']");

                            // ✅ Regenerate options using saved data (if not already loaded)
                            if ($stateSelect.children("option").length <= 1) {
                                $stateSelect.empty().append('<option value="">Select State</option>');
                                allStates.forEach(state => {
                                    $stateSelect.append(`<option value="${state.state_id}">${state.state_name}</option>`);
                                });
                            }

                            if ($aanchalSelect.children("option").length <= 1) {
                                $aanchalSelect.empty().append('<option value="">Select Aanchal</option>');
                                allAnchals.forEach(anchal => {
                                    $aanchalSelect.append(`<option value="${anchal.anchal_id}">${anchal.name}</option>`);
                                });
                            }

                            // ✅ Now set the selected values
                            $stateSelect.val(stateId).trigger("change.select2");
                            $aanchalSelect.val(anchalId).trigger("change.select2");

                        } else {
                            Swal.fire("Oops!", "Location details not found.", "warning");
                        }
                    }).fail(function() {
                        Swal.fire("Error!", "Failed to fetch state and anchal for selected city.", "error");
                    });
                } else {
                    $("select[name='state']").val('').trigger('change.select2');
                    $("select[name='aanchal']").val('').trigger('change.select2');
                }
            });





       let isAlertOpen = false;
            //----------set select 2 for select box---------------------
            $('select[name="city"]').select2({
                theme: 'bootstrap4'
                , placeholder: "Select an option"
                , allowClear: true
                , width: '100%'
            });


    // Function to toggle the 'required' attribute
    function toggleRequired(field, condition) {
        if (condition) {
            field.prop("required", true);
        } else {
            field.prop("required", false);
        }
    }

    // Add Family Member
  function addFamilyMember(index, data = null) {
    let memberDiv = $(`
        <div class="row border rounded p-2 mb-2 bg-light family-member" data-index="${index}">

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">📱 मोबाइल नंबर <span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="tel" name="family_members[${index}][mobile]"
                           class="form-control family-mobile-input"
                           pattern="^[0-9]{10}$"
                           maxlength="10"
                           placeholder="10 अंक दर्ज करें"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           required>
                    <span class="input-group-text family-member-loader-${index}" style="display:none;">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </span>
                </div>
                <small class="text-muted">नंबर डालने के बाद Tab करें → नाम auto-fill होगा</small>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">👤 सदस्य का नाम <span style="color:red">*</span></label>
                <input type="text" name="family_members[${index}][name]"
                       class="form-control name-field family-name-field"
                       placeholder="API से auto-fill या manually लिखें"
                       required>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">👨 पिता/ पति का नाम</label>
                <input type="text" name="family_members[${index}][father_name]"
                       class="form-control name-field family-father-field">
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">उम्र <span style="color:red">*</span></label>
                <input type="number" name="family_members[${index}][age]"
                       class="form-control" min="0" required>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">लिंग (Gender) <span style="color:red;">*</span></label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="family_members[${index}][gender]" value="male" required>
                        <label class="form-check-label">पुरुष</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="family_members[${index}][gender]" value="female">
                        <label class="form-check-label">महिला</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label fw-semibold">🪪 आधार नंबर</label>
                <input type="text" name="family_members[${index}][aadhar_number]"
                       class="form-control aadhar-field" maxlength="12"
                       pattern="^[0-9]{12}$" placeholder="12 अंकों का आधार"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            </div>

            <div class="col-md-3 mb-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-member mx-1">✖ हटाएं</button>
                <button type="button" class="btn btn-success btn-sm add-member">➕</button>
            </div>
        </div>
    `);

    // Pre-fill data if available (for cache restore)
    if (data) {
        memberDiv.find(`[name='family_members[${index}][mobile]']`).val(data.mobile);
        memberDiv.find(`[name='family_members[${index}][name]']`).val(data.name);
        memberDiv.find(`[name='family_members[${index}][father_name]']`).val(data.father_name);
        memberDiv.find(`[name='family_members[${index}][age]']`).val(data.age);
        memberDiv.find(`[name='family_members[${index}][gender]'][value='${data.gender}']`).prop('checked', true);
        memberDiv.find(`[name='family_members[${index}][aadhar_number]']`).val(data.aadhar_number);
    }

    familyMembersSection.append(memberDiv);

    // ✅ API fetch on mobile input — 10 digits pe instantly trigger
    const mobileInput = memberDiv.find('.family-mobile-input')[0];
    const nameInput   = memberDiv.find('.family-name-field')[0];
    const fatherInput = memberDiv.find('.family-father-field')[0];

    $(mobileInput).on('input', function () {
        const phone = $(this).val().trim();
        $(nameInput).val('');
        $(fatherInput).val('');
        if (phone.length !== 10) return;

        showApiLoader(); // 🔒 Screen freeze

        $.ajax({
            url: 'https://apiv1.sadhumargi.com/api/fetch-profiles',
            method: 'POST',
            headers: {
                'Authorization': 'Bearer vPW6doIdkAdf',
                'Accept': 'application/json'
            },
            data: { mobile_number: phone },
            success: function (response) {
                if (response.profiles && response.profiles.length > 0) {
                    if (response.profiles.length === 1) {
                        // ✅ Single → direct fill
                        const p = response.profiles[0];
                        $(nameInput).val(((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase());
                        $(fatherInput).val((p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase());
                        fillExtraFields(p, memberDiv[0]); // 📍 Auto-fill age, gender, aadhar
                    } else {
                        // ✅ Multiple → SweetAlert selection
                        let optionsHtml = response.profiles.map((p, i) =>
                            `<option value="${i}">${p.first_name} ${p.last_name} — ${p.father_name || p.fathers_name || ''}</option>`
                        ).join('');
                        Swal.fire({
                            title: 'सदस्य चुनें',
                            html: `<select id="swal-family-select" class="form-select mt-2">${optionsHtml}</select>`,
                            confirmButtonText: 'चुनें',
                            showCancelButton: true,
                            cancelButtonText: 'रद्द करें',
                            preConfirm: () => document.getElementById('swal-family-select').value
                        }).then(result => {
                            if (result.isConfirmed) {
                                const p = response.profiles[parseInt(result.value)];
                                $(nameInput).val(((p.first_name||'') + ' ' + (p.last_name||'')).trim().toUpperCase());
                                $(fatherInput).val((p.father_name || p.fathers_name || p.guardian_name || '').toUpperCase());
                                fillExtraFields(p, memberDiv[0]); // 📍 Auto-fill age, gender, aadhar
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'info', title: 'प्रोफ़ाइल नहीं मिली',
                        text: 'इस नंबर से प्रोफ़ाइल नहीं मिली। कृपया नाम manually भरें।',
                        confirmButtonText: 'ठीक है'
                    });
                    $(nameInput).focus();
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'warning', title: 'API Error',
                    text: 'डाटा प्राप्त नहीं हो रहा। कृपया नाम manually भरें।',
                    confirmButtonText: 'ठीक है'
                });
                $(nameInput).focus();
            },
            complete: function () {
                hideApiLoader(); // 🔓 Screen unfreeze
            }
        });
    });

    updatePeopleCount();
}


    // Update people count based on added family members
    function updatePeopleCount() {
        let count = $(".family-member").length;
        noOfPeopleInput.val(count);
    }

    // Generate family member fields based on the input number of people
    function generateFamilyMemberFields(count) {
        familyMembersSection.html("");
        for (let i = 1; i <= count; i++) {
            addFamilyMember(i);
        }
    }

    // Add new family member
    familyMembersSection.on("click", ".add-member", function () {
        let newIndex = $(".family-member").length + 1;
        if (newIndex > 9) {
            Swal.fire({
                icon: "warning",
                title: "Limit Exceeded",
                text: "आप (Head) सहित अधिकतम 10 व्यक्ति ही हो सकते हैं।",
            });
            return;
        }
        addFamilyMember(newIndex);
        updateGenderCount();
    });

    // Auto-calculate total male / female from head + members
    function updateGenderCount() {
        let maleCount = 0;
        let femaleCount = 0;

        // Count head's gender
        let headGender = $("input[name='gender']:checked").val();
        if (headGender === 'male')   maleCount++;
        if (headGender === 'female') femaleCount++;

        // Count each family member's gender
        $(".family-member").each(function () {
            let idx = $(this).data('index');
            let memberGender = $(this).find(`input[name='family_members[${idx}][gender]']:checked`).val();
            if (memberGender === 'male')   maleCount++;
            if (memberGender === 'female') femaleCount++;
        });

        $("input[name='total_male']").val(maleCount);
        $("input[name='total_female']").val(femaleCount);
    }

    // 📍 Expose to global scope for the modal selection
    window.addFamilyMember = addFamilyMember;
    window.updatePeopleCount = updatePeopleCount;
    window.updateGenderCount = updateGenderCount;

    // Trigger on head gender change
    $(document).on('change', 'input[name="gender"]', function () {
        updateGenderCount();
    });

    // Trigger on any member gender change
    familyMembersSection.on('change', 'input[type="radio"]', function () {
        updateGenderCount();
    });

    // Remove family member
    familyMembersSection.on("click", ".remove-member", function () {
        $(this).closest(".family-member").remove();
        updatePeopleCount();
        updateGenderCount();
        updateSixtyPlusCount();
    });

    // Validate the number of people
    noOfPeopleInput.on("blur", function () {
        let count = parseInt($(this).val());
        if (isNaN(count) || count < 1 || count > 9) {
            Swal.fire({
                icon: "error",
                title: "Invalid Number",
                text: "सदस्यों की संख्या 1 से 9 के बीच होनी चाहिए। (Head सहित अधिकतम 10 व्यक्ति)",
            });
            $(this).val("");
            familyMembersSection.html("");
        } else {
            generateFamilyMemberFields(count);
            updateGenderCount();
        }
    });

    // Hide family-related fields
    function hideFamilyFields() {
        peopleCount.hide();
        totalMale.hide();
        totalFemale.hide();
        sixtyPlusOption.hide();
        sixtyPlusMale.hide();
        sixtyPlusFemale.hide();
        familyMembersSection.html("");
    }

    hideFamilyFields();

    // Toggle the visibility of family fields based on radio selection
  function toggleFamilyFields(show) {
    if (show) {
        // Restore family fields
        peopleCount.show();
        totalMale.show();
        totalFemale.show();
        sixtyPlusOption.show();

        $("#no_of_people").val(cachedFamilyData.no_of_people);
        $("#no_of_children").val(cachedFamilyData.no_of_children);
        $("input[name='total_male']").val(cachedFamilyData.total_male);
        $("input[name='total_female']").val(cachedFamilyData.total_female);
        $("#sixty_plus_option").val(cachedFamilyData.sixty_plus_option);
        $("input[name='sixty_plus_male']").val(cachedFamilyData.sixty_plus_male);
        $("input[name='sixty_plus_female']").val(cachedFamilyData.sixty_plus_female);

        // Recreate family members
        familyMembersSection.empty();
        cachedFamilyData.members.forEach((member, index) => {
            addFamilyMember(index, member); // Pass data here
        });
    } else {
        // Cache the data before hiding
        cachedFamilyData.no_of_people = $("#no_of_people").val();
        cachedFamilyData.no_of_children = $("#no_of_children").val();
        cachedFamilyData.total_male = $("input[name='total_male']").val();
        cachedFamilyData.total_female = $("input[name='total_female']").val();
        cachedFamilyData.sixty_plus_option = $("#sixty_plus_option").val();
        cachedFamilyData.sixty_plus_male = $("input[name='sixty_plus_male']").val();
        cachedFamilyData.sixty_plus_female = $("input[name='sixty_plus_female']").val();

        // Cache each family member's data
        cachedFamilyData.members = [];
        $(".family-member").each(function () {
            let $row = $(this);
            let index = $row.data("index");
            cachedFamilyData.members.push({
                name: $row.find(`[name='family_members[${index}][name]']`).val(),
                father_name: $row.find(`[name='family_members[${index}][father_name]']`).val(),
                age: $row.find(`[name='family_members[${index}][age]']`).val(),
                gender: $row.find(`[name='family_members[${index}][gender]']:checked`).val(),
                mobile: $row.find(`[name='family_members[${index}][mobile]']`).val(),
                aadhar_number: $row.find(`[name='family_members[${index}][aadhar_number]']`).val()
            });
        });

        hideFamilyFields();
    }
}


    // Toggle the visibility of sixty-plus fields
    function toggleSixtyPlusFields() {
        if ($("#sixty_plus_option").val() === "yes") {
            sixtyPlusMale.show();
            sixtyPlusFemale.show();
        } else {
            sixtyPlusMale.hide();
            sixtyPlusFemale.hide();
        }
    }

    // Auto-calculate 60+ counts from age inputs
    function updateSixtyPlusCount() {
        let sixtyMale = 0;
        let sixtyFemale = 0;

        // Check head's age and gender
        let headAge = parseInt($("input[name='age']").val()) || 0;
        let headGender = $("input[name='gender']:checked").val();
        if (headAge >= 60) {
            if (headGender === 'male')   sixtyMale++;
            if (headGender === 'female') sixtyFemale++;
        }

        // Check each family member's age and gender
        $(".family-member").each(function () {
            let idx = $(this).data('index');
            let memberAge = parseInt($(this).find(`input[name='family_members[${idx}][age]']`).val()) || 0;
            let memberGender = $(this).find(`input[name='family_members[${idx}][gender]']:checked`).val();
            if (memberAge >= 60) {
                if (memberGender === 'male')   sixtyMale++;
                if (memberGender === 'female') sixtyFemale++;
            }
        });

        let total = sixtyMale + sixtyFemale;

        // Auto-set the dropdown
        $("#sixty_plus_option").val(total > 0 ? 'yes' : 'no');

        // Show/hide count fields
        if (total > 0) {
            sixtyPlusMale.show();
            sixtyPlusFemale.show();
            $("input[name='sixty_plus_male']").val(sixtyMale);
            $("input[name='sixty_plus_female']").val(sixtyFemale);
        } else {
            sixtyPlusMale.hide();
            sixtyPlusFemale.hide();
            $("input[name='sixty_plus_male']").val(0);
            $("input[name='sixty_plus_female']").val(0);
        }
    }

    // Trigger on head's age change
    $(document).on('input change', 'input[name="age"]', function () {
        updateSixtyPlusCount();
    });

    // Trigger on head's gender change (age may already be 60+)
    $(document).on('change', 'input[name="gender"]', function () {
        updateSixtyPlusCount();
    });

    // Trigger on any member's age or gender change
    familyMembersSection.on('input change', 'input[type="number"], input[type="radio"]', function () {
        updateSixtyPlusCount();
    });

 $(document).ready(function () {
    // 🛑 Block alphabet characters on Aadhaar input
    $(document).on('input', 'input[name="aadhar_number"], input[name^="family_members"][name$="[aadhar_number]"]', function () {
        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
    });





    // ✅ Check for existing Aadhaar on blur
   $(document).on('blur', 'input[name="aadhar_number"], input[name^="family_members"][name$="[aadhar_number]"]', function () {
    let aadharInput = $(this);
    let aadharNumber = aadharInput.val().trim();

    // Skip if not 12 digits
     if (!/^\d{12}$/.test(aadharNumber)) {
        Swal.fire({
            icon: 'warning',
            title: 'अवैध आधार नंबर',
            text: 'कृपया 12 अंकों का वैध आधार नंबर दर्ज करें।',
            confirmButtonText: 'ठीक है'
        });
        aadharInput.val('');
        return;
    }


    // 🔁 Check duplicate within the form
    let duplicate = false;
    $('input[name="aadhar_number"], input[name^="family_members"][name$="[aadhar_number]"]').not(this).each(function () {
        if ($(this).val().trim() === aadharNumber) {
            duplicate = true;
            return false;
        }
    });

    if (duplicate) {
        Swal.fire({
            icon: 'error',
            title: 'डुप्लीकेट आधार नंबर',
            text: 'यह आधार नंबर पहले से दर्ज किया गया है। कृपया अलग नंबर डालें।',
            confirmButtonText: 'ठीक है'
        });
        aadharInput.val('');
        return;
    }

    // 🟢 If no duplicate, then check with backend
    $.ajax({
        url: "{{ route('check.aadhar') }}",
        method: 'POST',
        data: {
            aadhar_number: aadharNumber,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.exists) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Aadhaar (Server)',
                    text: 'यह आधार नंबर पहले से डेटाबेस में मौजूद है।',
                    confirmButtonText: 'ठीक है'
                });
                aadharInput.val('');
            }
        }
    });
});





});

$(document).ready(function () {
    // Allow only letters and space in name and father's name
    $('input[name="name"], input[name="father_name"]').on('input', function () {
        this.value = this.value.replace(/[^a-zA-Z\u0900-\u097F\s]/g, ''); // English + Hindi + spaces
    });
});

$(document).ready(function () {
    // Allow only digits in phone and aadhar_number fields
    $('input[name="phone"], input[name="aadhar_number"], input[name="age"]').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
const checkInInput = document.querySelector("input[name='check_in_date']");
const checkoutInput = document.querySelector("input[name='check_out_date']");

const formatDate = (date) => {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
};

checkInInput.addEventListener("change", function () {
    const checkInDate = new Date(this.value);
    if (isNaN(checkInDate)) return;

    // Update checkout min to check-in date
    checkoutInput.min = formatDate(checkInDate);

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

    if (!isNaN(checkInDate) && checkoutDate < checkInDate) {
        alert("प्रस्थान की दिनांक आगमन की दिनांक से पहले नहीं हो सकती");
        this.value = '';
    }
});



    // Event for "Family Coming" selection
    $("input[name='family_coming']").on("change", function () {
        toggleFamilyFields($(this).val() === "1");
    });

    // Handle hidden fields visibility based on trigger
    $("#trigger").change(function() {
        if ($(this).val() === "yes") {
            $("#hiddenFields").removeClass("hidden");
            $("#extraInfo").prop("required", true);
        } else {
            $("#hiddenFields").addClass("hidden");
            $("#extraInfo").prop("required", false);
        }
    });

    $("#submitButton").on("click", function (e) {
        e.preventDefault(); // Stop default form submission


         let familyComing = $("input[name='family_coming']:checked").val();

    // Step 0: If "No", clear all related values
    if (familyComing === "0") {
        // Clear all family-related values before submission
        $("input[name='no_of_people']").val("");
        $("input[name='no_of_children']").val("");
        $("input[name='total_male']").val("");
        $("input[name='total_female']").val("");
        $("input[name='sixty_plus_male']").val("");
        $("input[name='sixty_plus_female']").val("");
        $("#sixty_plus_option").val("no");
        $("#family_members_section").html(""); // remove family member blocks
    }


        // Step 1: Validate required visible fields
        let invalidFields = [];
        $("#bookingForm")
            .find("input, select, textarea")
            .each(function () {
                let $el = $(this);
                if ($el.is(":visible") && $el.prop("required") && !$el.val()) {
                    invalidFields.push($el);
                }
            });

        if (invalidFields.length > 0) {
            Swal.fire({
                icon: "error",
                title: "⚠️ सभी जरूरी फ़ील्ड भरें",
                text: "कृपया सभी दिखाई दे रहे आवश्यक फ़ील्ड भरें।",
                confirmButtonText: "ठीक है"
            });
            invalidFields[0].focus();
            return;
        }

        // Step 2: Confirmation dialog
        Swal.fire({
            title: "पुष्टि करें",
            text: "क्या आप वाकई फॉर्म सबमिट करना चाहते हैं?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "हाँ, सबमिट करें",
            cancelButtonText: "नहीं"
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData($("#bookingForm")[0]);
                $("#formLoader").show();

                $.ajax({
                    url: "{{ route('family-booking.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function (response) {
                        $("#formLoader").hide();

                        if (response.success && response.redirect) {
                            // Optional success alert before redirect
                            Swal.fire({
                                icon: "success",
                                title: "✅ सफलतापूर्वक सबमिट",
                                text: "आपका फॉर्म सफलतापूर्वक सबमिट हो गया है।",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire("Success", "फॉर्म सबमिट हुआ!", "success");
                            $("#bookingForm")[0].reset();
                        }
                    },
                    error: function (xhr) {
                        $("#formLoader").hide();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let msg = "";
                            $.each(errors, function (key, val) {
                                msg += val[0] + "\n";
                            });

                            Swal.fire("Validation Error", msg, "error");
                        } else {
                            Swal.fire("Error", "कुछ गलत हो गया है।", "error");
                        }
                    }
                });
            }
        });
    });


});

</script>
<script>
    document.querySelectorAll('input[name="is_veer_parivar"]').forEach((elem) => {
        elem.addEventListener('change', function() {
            const relationGroup = document.getElementById('veer-relation-group');
            const nameGroup = document.getElementById('ms-name-group'); // 👈 New line added

            if (this.value == '1') {
                relationGroup.style.display = 'block';
                nameGroup.style.display = 'block'; // 👈 Show ms_name field
            } else {
                relationGroup.style.display = 'none';
                nameGroup.style.display = 'none'; // 👈 Hide ms_name field
                document.querySelector('select[name="veer_relation"]').value = '';
                document.querySelector('input[name="ms_name"]').value = ''; // 👈 Clear value
            }
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
        
        for (let i = 0; i < inputs.length; i++) {
            // Skip validation for elements whose parent containers are hidden
            if ($(inputs[i]).parents(':hidden').length > 0) {
                continue;
            }
            
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

<!-- Bootstrap 5 CSS  --> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 JS (Modal के लिए ज़रूरी)  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>





