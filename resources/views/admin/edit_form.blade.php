@extends('admin.layout')

@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Form Record</h2>
    
    <div class="card shadow p-4">
        <form action="{{ route('forms.update', $form->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $form->name }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="{{ $form->phone }}" maxlength="10" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">MID</label>
        <input type="text" name="mid" class="form-control" value="{{ $form->mid }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">Aadhar Number</label>
        <input type="text" name="aadhar_number" class="form-control" value="{{ $form->aadhar_number }}"  maxlength="12" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">City</label>
        <select name="city" class="form-select">
            <option value="City1" {{ $form->city == 'City1' ? 'selected' : '' }}>City1</option>
            <option value="City2" {{ $form->city == 'City2' ? 'selected' : '' }}>City2</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">State</label>
        <select name="state" class="form-select">
            <option value="State1" {{ $form->state == 'State1' ? 'selected' : '' }}>State1</option>
            <option value="State2" {{ $form->state == 'State2' ? 'selected' : '' }}>State2</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label fw-bold">Aanchal</label>
        <select name="aanchal" class="form-select">
            <option value="Aanchal1" {{ $form->aanchal == 'Aanchal1' ? 'selected' : '' }}>Aanchal1</option>
            <option value="Aanchal2" {{ $form->aanchal == 'Aanchal2' ? 'selected' : '' }}>Aanchal2</option>
        </select>
    </div>
</div>


<div class="col-md-8 mb-3">
    <label class="form-label"><span style="color: red;">*</span> आप संघ के किस इकाई से सम्बंध रखते हैं</label>
    <div class="department-container border ">
        <div class="d-flex flex-column gap-2">
            <label class="form-check-label">
                <input type="radio" name="department" value="श्री संघ" required> श्री संघ
            </label>
            <label class="form-check-label">
                <input type="radio" name="department" value="महिला समिति" required> महिला समिति
            </label>
            <label class="form-check-label">
                <input type="radio" name="department" value="युवा संघ"> युवा संघ
            </label>
        </div>
    </div>
</div>


            <div class="col-md-12 mb-3">
            <div class="post-selection-box p-3 rounded shadow-sm bg-white border border-primary">
    <label class="form-label fw-bold">
        <span style="color: red;">*</span> आप संघ में किस पद पर हैं?
    </label>
    <div class="post-container d-flex flex-wrap gap-3">
        <label class="form-check-label">
            <input type="radio" name="post" value="PST" required> PST
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="Ex-PST" required> Ex-PST
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="शिखर सदस्य"> शिखर सदस्य
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="महाप्रभावक सदस्य"> महाप्रभावक सदस्य
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="नियामक परिषद"> नियामक परिषद
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="राष्ट्रीय प्रभारी"> राष्ट्रीय प्रभारी (युवा संघ)
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="संभाग प्रमुखा"> संभाग प्रमुखा (महिला समिति)
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="उपाध्यक्ष/मंत्री"> उपाध्यक्ष/मंत्री
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="कार्यसमिति सदस्य"> कार्यसमिति सदस्य
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="प्रवृत्ति संयोजिका/संयोजक/ संयोजन मण्डल सदस्य">
            प्रवृत्ति संयोजिका / संयोजक / संयोजन मण्डल सदस्य
        </label>
        <label class="form-check-label">
            <input type="radio" name="post" value="विशेष आमंत्रित"> विशेष आमंत्रित
        </label>
    </div>
</div>

<div class="row align-items-center">
    <!-- ✅ उपस्थिति प्रश्न (छोटा साइज - 5 कॉलम) -->
    <div class="col-md-5 mb-3">
        <label class="form-label fw-bold">  <span style="color: red;">*</span>क्या आप कार्यसमिति बैठक हेतु कवर्धा में उपस्थित हो रहे हैं ?*</label>
        <div class="border rounded p-3 bg-light text-dark" style="border: 2px solid #007bff;">
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_coming" value="1" id="is_coming_yes" required style="accent-color: #28a745;">
                    <label class="form-check-label fw-semibold text-success" for="is_coming_yes">हाँ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_coming" value="0" id="is_coming_no" required style="accent-color: #dc3545;">
                    <label class="form-check-label fw-semibold text-danger" for="is_coming_no">नहीं</label>
                </div>
            </div>
        </div>
    </div>

            <div class="col-md-7 mb-3 stay-arrangement" style="display: none;">
    <label class="form-label fw-semibold">  <span style="color: red;">*</span>रहने की व्यवस्था*</label>
        <div class="p-3 border rounded bg-light" style="border: 2px solid #28a745;">
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stay_arrangement" value="संघ की व्यवस्था" id="stay_sangh" required>
                    <label class="form-check-label text-success" for="stay_sangh">संघ की व्यवस्था</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stay_arrangement" value="स्वयं की व्यवस्था" id="stay_self" required>
                    <label class="form-check-label text-danger" for="stay_self">स्वयं की व्यवस्था</label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-3" id="submitBtn" style="display:none;">✅ Update </button>
            <div class="travel-details p-3 rounded shadow-sm bg-white" style="display:none; border: 2px solid #007bff;">
    <h4 class="section-title text-center text-primary fw-bold mb-3">यात्रा विवरण (Travel Details)</h4>
    
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>आने का वाहन</label>
            <select name="travel_type" class="form-select shadow-sm" >
                <option value="">आने का वाहन</option>
                <option value="Flight">✈️ विमान</option>
                <option value="Train">🚆 ट्रैन</option>  
                <option value="Bus">🚌 बस</option>
                <option value="Car">🚗 कार</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>आगमन की दिनांक</label>
            <input type="date" name="check_in_date" class="form-control shadow-sm rounded" >
        </div>


        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>आगमन का समय</label>
            <input type="time" name="check_in_time" class="form-control shadow-sm rounded" >
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>प्रस्थान की दिनांक</label>
            <input type="date" name="check_out_date" class="form-control shadow-sm rounded" >
        </div>

        

        <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">  <span style="color: red;">*</span>प्रस्थान का समय</label>
            <input type="time" name="check_out_time" class="form-control shadow-sm rounded" >
        </div>
    </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Form</button>
                <a href="{{ route('forms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    let isAlertOpen = false; 
            
    // ✅ पहले सब कुछ छुपाएँ
    $(".travel-details").hide();
    $(".stay-arrangement").hide();
    $("#submitBtn").hide();
    $("#otherPostField").hide();

    // ✅ "क्या आप आ रहे हैं?" (Yes/No) का Event
    $("input[name='is_coming']").change(function () {
        if ($(this).val() == "1") {
            $(".stay-arrangement").show(); // रहने की व्यवस्था दिखाएँ
            $(".stay-arrangement input").prop("disabled", false);
            $("#submitBtn").hide();
        } else {
            $(".stay-arrangement, .travel-details").hide();
            $(".stay-arrangement input, .travel-details input").prop("disabled", true);
            $("#submitBtn").show();
        }
    });

    // ✅ रहने की व्यवस्था चेंज इवेंट
    $("input[name='stay_arrangement']").change(function () {
        if ($(this).val() === "संघ की व्यवस्था") {
            $(".travel-details").show();
            $(".travel-details input").prop("disabled", false);
            $("#submitBtn").hide();
        } else {
            $(".travel-details").hide();
            $(".travel-details input").prop("disabled", true);
            $("#submitBtn").show();
        }
    });

    // ✅ नाम फील्ड वैलिडेशन (केवल अक्षर + ऑटो अपरकेस)
    $("input[name='name']").on("input", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '').toUpperCase();
    });

    // ✅ मोबाइल और आधार नंबर इनपुट क्लीनर (सिर्फ अंक)  
    $("input[name='phone'], input[name='aadhar_number']").on("input", function () {
        this.value = this.value.replace(/\D/g, '');
    });

    // ✅ मोबाइल नंबर वैलिडेशन
    $("input[name='phone']").on("blur", function () {
        if (!isAlertOpen) {
            checkLength(this, 10, "मोबाइल नंबर");
        }
    });

    // ✅ आधार नंबर वैलिडेशन और डुप्लीकेट चेक
    $("input[name='aadhar_number']").on("blur", function () {
        if (!isAlertOpen) {
            validateAadhar(this, 12);
        }
    });

    function checkLength(field, length, fieldName) {
        let $field = $(field);
        let value = $field.val().trim();

        if (value.length !== length) {
            isAlertOpen = true; 
            Swal.fire("Error!", `${fieldName} को ${length} अंकों का होना चाहिए.`, "error").then(() => {
                $field.val("");
                setTimeout(() => { 
                    isAlertOpen = false;
                }, 10);
            });
        }
    }
    function checkDuplicateAadhar($field) {
    let value = $field.val().trim();

    if (value) {
        $.ajax({
            url: "/check-duplicate",
            type: "POST",
            data: { aadhar_number: value },
            success: function (response) {
                if (response.exists) {
                    isAlertOpen = true;
                    Swal.fire("Error!", "आधार नंबर पहले से मौजूद है! कृपया अलग विवरण दर्ज करें.", "error").then(() => {
                        $field.val(""); 
                        setTimeout(() => { 
                            isAlertOpen = false;
                        }, 10);
                    });
                }
            },
            error: function () {
                Swal.fire("Error!", "Duplicate check failed. Try again.", "error");
            }
        });
    }
}

function validateAadhar(field, length) {
    let $field = $(field);
    let value = $field.val().trim();

    if (value.length !== length) {
        isAlertOpen = true;
        Swal.fire("Error!", `आधार नंबर को ${length} अंकों का होना चाहिए.`, "error").then(() => {
            $field.val("");
            setTimeout(() => { 
                isAlertOpen = false;
            }, 10);
        });
        return; 
    }

    
    checkDuplicateAadhar($field);
}

    // ✅ फ़ॉर्म सबमिट इवेंट
    $("#updateData").submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Confirm Submission",
            text: "क्या आप सभी जानकारी सही से भर चुके हैं?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, Submit",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/submit-form",
                    type: "POST",
                    data: $("#combinedForm").serialize(),
                    beforeSend: function () {
                        Swal.fire({
                            title: "Processing...",
                            text: "कृपया प्रतीक्षा करें...",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function (response) {
                        Swal.fire("Success!", response.message, "success");
                        $("#updateData")[0].reset();
                        $(".travel-details, .stay-arrangement").hide();
                        $("#submitBtn").hide();
                    },
                    error: function () {
                        Swal.fire("Error!", "Submission failed. Try again.", "error");
                    }
                });
            }
        });
    });
    $("#submitBtn").click(function () {
    if ($("input[name='stay_arrangement']:checked").val() === "संघ की व्यवस्था") {
        let travelValid = $("select[name='travel_type']").val() && 
                          $("input[name='check_in_date']").val() && 
                          $("input[name='check_out_date']").val();
        if (!travelValid) {
            Swal.fire("Error!", "कृपया यात्रा विवरण भरें!", "error");
            return false;
        }
    }
});


    // ✅ Laravel के लिए CSRF टोकन सेट करें
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ✅ बूटस्ट्रैप मोडल ऑटो ओपन करें
    let myModal = new bootstrap.Modal(document.getElementById("myModal"));
    myModal.show();
});

</script>
@endsection
