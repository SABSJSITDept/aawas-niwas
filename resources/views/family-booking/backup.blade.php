<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Family Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand text-white d-flex align-items-center" href="#">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50" height="34" class="me-2">
      ⚡ श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ 📝
    </a>
    <a href="{{ route('home') }}" class="btn btn-light">Home</a>
  </div>
</nav>
<!-- Gap between navbar and card -->
<div class="mb-4"></div>  
<div class="container">
    <div class="card p-4">
     
        
        <div class="header-container text-center p-3 rounded shadow-sm" 
             style="background: linear-gradient(to right, rgb(99, 122, 146), #dff3ff); 
                    border: 4px solid #007bff;
                    margin-bottom: 20px;">  
            
            <!-- Banner -->
            <div class="banner bg-danger text-white p-3 rounded d-inline-block" 
                 style="font-size: 22px; font-weight: bold;">
                कार्यसमिति बैठक (सत्र 23-25)
            </div>

            <!-- Location Text with Some Space -->
            <h2 class="mt-4" 
                style="color: #002f6c; font-weight: bold; text-shadow: 1px 1px 3px rgba(25, 2, 2, 0.2);">
                स्थान - गंगाशहर - भीनासर / जिला - बीकानेर (राजस्थान)
            </h2>

            <!-- Date -->
            <h3 class="text-danger fw-bold mt-3">28-29 अप्रैल 2025</h3>
        </div>

<!-- Main Content -->
<div class="container px-1"> <!-- Added padding -->
<form id="bookingForm" method="POST" action="{{ route('family-booking.store') }}">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Name</label>  
                    <input type="text" name="name" class="form-control" required>
                </div>
            
                <div class="col-md-3">
                    <label class="form-label">Father Name</label>  
                    <input type="text" name="father_name" class="form-control" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Mobile Numbers  </label>
                    <input type="text" name="phone" class="form-control" maxlength="10" required>
                </div>  

                <div class="col-md-3 mb-3">
                    <label class="form-label">आधार कार्ड नंबर</label>
                    <input type="text" name="aadhar_number" class="form-control" maxlength="12" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">City</label>
                    <select name="city" class="form-select">
                        <option value="City1">City1</option>
                        <option value="City2">City2</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">State</label>
                    <select name="state" class="form-select">
                      
    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>

<option value="Andhra Pradesh">Andhra Pradesh</option>

<option value="Arunachal Pradesh">Arunachal Pradesh</option>

<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>




<option value="Telangana">Telangana</option>

<option value="Tripura">Tripura</option>

<option value="Uttarakhand">Uttarakhand</option>

<option value="West Bengal">West Bengal</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                <label class="form-label">Aanchal</label>
                <select name="aanchal" class="form-select">
                    <option value="Aanchal1">Aanchal1</option>
                    <option value="Aanchal2">Aanchal2</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
    <label class="form-label">Family Coming?</label>
    <div class="border p-1 rounded">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="family_coming" value="1" required>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="family_coming" value="0" required>
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="col-md-3 mb-3" id="people_count"  > 
    <label class="form-label">Number of People (*excluding you)</label>
    <input type="number" name="no_of_people" class="form-control" id="no_of_people" min="1" max="10">
    <small id="people_error" class="text-danger d-none">Value must be between 2 and 10</small>
</div>

<!-- Family Members Details  -->
<div id="family_members_section"></div>


<div class="col-md-3 mb-3 family-dependent">
    <label class="form-label">Total Male</label>
    <input type="number" name="total_male" class="form-control">
</div>

<div class="col-md-3 mb-3 family-dependent">
    <label class="form-label">Total Female</label>
    <input type="number" name="total_female" class="form-control">
</div>

<div class="col-md-3 mb-3 family-dependent">
    <label class="form-label">60+ Members Are There?</label>
    <select id="sixty_plus_option" class="form-control">
        <option value="no">No</option>
        <option value="yes">Yes</option>
    </select>
</div>

<div class="col-md-1 mb-6 family-dependent" id="sixty_plus_male">
    <label class="form-label">60+ Male</label>
    <input type="number" name="sixty_plus_male" class="form-control">
</div>

<div class="col-md-1 mb-6 family-dependent" id="sixty_plus_female">
    <label class="form-label">60+ Female</label>
    <input type="number" name="sixty_plus_female" class="form-control">
</div>



<div class="travel-details p-3 rounded shadow-sm bg-white"  border: 2px solid #007bff;">
    <h4 class="section-title text-center text-primary fw-bold mb-3">यात्रा विवरण (Travel Details)</h4>
    
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">आने का वाहन*</label>
            <select name="travel_type" class="form-select shadow-sm" required >
                <option value="">आने का वाहन</option>
                <option value="Flight">✈️ विमान</option>
                <option value="Train">🚆 ट्रैन</option>  
                <option value="Bus">🚌 बस</option>
                <option value="Car">🚗 कार</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">आगमन की दिनांक*</label>
            <input type="date" name="check_in_date" class="form-control shadow-sm rounded" required>
        </div>


        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">आगमन का समय*</label>
            <input type="time" name="check_in_time" class="form-control shadow-sm rounded" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">प्रस्थान की दिनांक*</label>
            <input type="date" name="check_out_date" class="form-control shadow-sm rounded" required>
        </div>

        

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">प्रस्थान का समय*</label>
            <input type="time" name="check_out_time" class="form-control shadow-sm rounded" required >
        </div>
    </div>
    <button type="submit" id="submit_button" class="btn btn-primary w-100 mt-3 fw-bold shadow">
    ✅ सबमिट करें
</button>

       </div>
            </div>
        </form>
    </div>
</div>

<!-- Footer  -->
<footer class="footer bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">&copy; 2025 श्री साधुमार्गी जैन संघ | All Rights Reserved.</p>
    </div>
</footer>
<script>
$(document).ready(function () {
    
    let peopleCount = $("#people_count");
    let totalMale = $("input[name='total_male']").closest(".family-dependent");
    let totalFemale = $("input[name='total_female']").closest(".family-dependent");
    let sixtyPlusOption = $("#sixty_plus_option").closest(".family-dependent");
    let sixtyPlusMale = $("#sixty_plus_male").closest(".family-dependent");
    let sixtyPlusFemale = $("#sixty_plus_female").closest(".family-dependent");
    let noOfPeopleInput = $("input[name='no_of_people']");
    let familyMembersSection = $("#family_members_section");

    function addFamilyMember(index) {
        let memberDiv = $(`  
            <div class="row border rounded p-2 mb-2 bg-light family-member" data-index="${index}">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Member Name</label>
                    <input type="text" name="family_members[${index}][name]" class="form-control" required>
                </div>
                 <div class="col-md-3 mb-2">
                    <label class="form-label">Member Father Name</label>
                    <input type="text" name="family_members[${index}][father_name]" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Phone</label>
                    <input type="text" name="family_members[${index}][mobile]" class="form-control" maxlength="10" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Aadhar Number</label>
                    <input type="text" name="family_members[${index}][aadhar_number]" class="form-control" maxlength="12" required>
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-member mx-1">✖</button>
                    <button type="button" class="btn btn-success btn-sm add-member">➕</button>
                </div>
            </div>
        `);

        familyMembersSection.append(memberDiv);
        updatePeopleCount();
    }

    function updatePeopleCount() {
        let count = $(".family-member").length;
        noOfPeopleInput.val(count);
    }

    function generateFamilyMemberFields(count) {
        familyMembersSection.html("");
        for (let i = 1; i <= count; i++) {
            addFamilyMember(i);
        }
    }

    familyMembersSection.on("click", ".add-member", function () {
        let newIndex = $(".family-member").length + 1;
        if (newIndex > 10) {
            Swal.fire({
                icon: "warning",
                title: "Limit Exceeded",
                text: "You cannot add more than 10 members.",
            });
            return;
        }
        addFamilyMember(newIndex);
    });

    familyMembersSection.on("click", ".remove-member", function () {
        $(this).closest(".family-member").remove();
        updatePeopleCount();
    });

    noOfPeopleInput.on("blur", function () {
        let count = parseInt($(this).val());
        if (isNaN(count) || count < 1 || count > 10) {
            Swal.fire({
                icon: "error",
                title: "Invalid Number",
                text: "Number of people must be between 1 and 10.",
            });
            $(this).val("");
            familyMembersSection.html("");
        } else {
            generateFamilyMemberFields(count);
        }
    });

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

    function toggleFamilyFields(show) {
        if (show) {
            peopleCount.show();
            totalMale.show();
            totalFemale.show();
            sixtyPlusOption.show();
        } else {
            hideFamilyFields();
        }
    }

    function toggleSixtyPlusFields() {
        if ($("#sixty_plus_option").val() === "yes") {
            sixtyPlusMale.show();
            sixtyPlusFemale.show();
        } else {
            sixtyPlusMale.hide();
            sixtyPlusFemale.hide();
        }
    }

    $("input[name='family_coming']").on("change", function () {
        toggleFamilyFields($(this).val() === "1");
    });

    $("#sixty_plus_option").on("change", function () {
        toggleSixtyPlusFields();
    });

    $("#submit_button").on("click", function (e) { 
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to submit this form?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, submit it!",
            cancelButtonText: "No, cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData($("#bookingForm")[0]);

                $.ajax({
                    url: "{{ route('family-booking.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function (data) {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: "Family data saved successfully!",
                            });

                            $("#bookingForm")[0].reset();
                            hideFamilyFields();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong. Please try again.",
                            });
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = "Something went wrong.";
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = "";
                            $.each(errors, function (key, value) {
                                errorMessage += value[0] + "\n";
                            });

                            Swal.fire({
                                icon: "error",
                                title: "Validation Error",
                                text: errorMessage,
                            });

                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: xhr.responseJSON.message,
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong. Please try again.",
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Cancelled",
                    text: "Form submission was cancelled.",
                });
            }
        });
    });
    $("#trigger").change(function() {
            if ($(this).val() === "yes") {
                $("#hiddenFields").removeClass("hidden");
                $("#extraInfo").prop("required", true);
            } else {
                $("#hiddenFields").addClass("hidden");
                $("#extraInfo").prop("required", false);
            }
        });
});




</script>

<!-- Bootstrap 5 CSS  --> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 JS (Modal के लिए ज़रूरी) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>



<?php
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FamilyBookingController;
use App\Http\Controllers\GroupBookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

Route::get('/get-family-members/{id}', function ($id) {
    $members = FamilyMember::where('family_id', $id)->get();
    return response()->json($members);
});





Route::get('/export-forms', [FormController::class, 'exportForms'])->name('forms.export');


Route::get('/group-booking', [GroupBookingController::class, 'index'])->name('group.booking');//form view ke liye 
Route::post('/group-booking/store', [GroupBookingController::class, 'store'])->name('group.booking.store');// data ko save ke liye 
Route::get('/group-booking/create', [GroupBookingController::class, 'create'])->name('group.booking.create');//for saved data view ke liye
Route::get('/group-bookings/{id}/edit', [GroupBookingController::class, 'edit'])->name('group.booking.edit'); // Edit Form
Route::put('/group-bookings/{id}', [GroupBookingController::class, 'update'])->name('group.booking.update'); // Update
Route::delete('/group-bookings/{id}', [GroupBookingController::class, 'destroy'])->name('group.booking.destroy'); // Delete



Route::get('/', [HomeController::class, 'index'])->name('home');//home page ke liye
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');//admin dashboard ke liye

Route::get('/form', function () {
    return view('form');
})->name('form');//form view ke liye

Route::post('/submit-form', [FormController::class, 'store']);

Route::post('/submit-travel-form', [FormController::class, 'storeTravel']);

Route::get('/admin/forms', [FormController::class, 'index'])->name('admin.forms');


Route::get('/family-booking', [FamilyBookingController::class, 'index'])->name('family-booking.index');
Route::post('/family-booking/store', [FamilyBookingController::class, 'store'])->name('family-booking.store');
Route::get('/family-booking/create', [FamilyBookingController::class, 'create'])->name('family-booking.create');
Route::get('/family-booking/{id}/edit', [FamilyBookingController::class, 'edit'])->name('family-booking.edit');
Route::put('/family-booking/{id}', [FamilyBookingController::class, 'update'])->name('family-booking.update');
Route::delete('/family-booking/{id}', [FamilyBookingController::class, 'destroy'])->name('family-booking.destroy');



Route::get('/other_form', function () {
    return view('other_form');
})->name('other_form');

Route::get('/form_view', function () {
    return view('form_view');
})->name('form_view');

Route::get('/form_data_dar', function () {
    return view('form_data_dar');
})->name('form_data_dar');

Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
Route::get('/forms/{id}/edit', [FormController::class, 'edit'])->name('forms.edit');
Route::put('/forms/{id}', [FormController::class, 'update'])->name('forms.update');
Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('forms.destroy');
Route::post('/submit-form', [FormController::class, 'store']);
Route::post('/check-duplicate', [FormController::class, 'checkDuplicate']);


Route::get('/thank-you', function () {
    return view('thankyou');
})->name('thankyou');





Route::get('/hotels', [HotelController::class, 'index'])->name('hotel.index');
Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotel.create');
Route::post('/hotels/store', [HotelController::class, 'store'])->name('hotel.store');
Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('hotel.show');

Route::get('/hotels/{id}/edit', [HotelController::class, 'edit'])->name('hotel.edit');
Route::put('/hotels/{id}', [HotelController::class, 'update'])->name('hotel.update');
Route::delete('/hotels/{id}', [HotelController::class, 'destroy'])->name('hotel.destroy');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});



