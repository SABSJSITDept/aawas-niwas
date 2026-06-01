$(document).ready(function () {
    $("input[name='is_coming']").change(function () {
        if ($(this).val() == "1") {
            $(".travel-details").show();
            $(".travel-details input, .travel-details select").prop("disabled", false);
            $("#submitBtn").hide();
        } else {
            $(".travel-details").hide();
            $(".travel-details input, .travel-details select").prop("disabled", true);
            $("#submitBtn").show();
        }
    });

    $("#combinedForm").submit(function (e) {
        e.preventDefault();

        let phone = $("input[name='phone']").val();
        let aadhar = $("input[name='aadhar_number']").val();

        if (phone.length !== 10) {
            return Swal.fire("Error!", "Phone number must be exactly 10 digits.", "error");
        }
        if (aadhar.length !== 12) {
            return Swal.fire("Error!", "Aadhar number must be exactly 12 digits.", "error");
        }

        // ✅ Check duplicate entry in backend before submitting
        $.ajax({
            url: "/check-duplicate",
            type: "POST",
            data: { phone: phone, aadhar_number: aadhar },
            success: function (response) {
                if (response.exists) {
                    Swal.fire("Error!", "Phone or Aadhar already exists. Please enter different details.", "error");
                } else {
                    // ✅ Proceed with form submission
                    submitForm();
                }
            },
            error: function () {
                Swal.fire("Error!", "Could not check duplicate entry. Try again.", "error");
            }
        });
    });

    function submitForm() {
        Swal.fire({
            title: "Confirm Submission",
            text: "Are you sure all details are correct?",
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
                    beforeSend: function() {
                        Swal.fire({
                            title: "Processing...",
                            text: "Submitting your form, please wait.",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function (response) {
                        Swal.fire("Success!", response.message, "success");
                        $("#combinedForm")[0].reset();
                        $(".travel-details").hide();
                        $("#submitBtn").hide();
                    },
                    error: function () {
                        Swal.fire("Error!", "Submission failed. Try again.", "error");
                    }
                });
            }
        });
    }
});

$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const otherPostRadio = document.getElementById("otherPost");
        const otherPostField = document.getElementById("otherPostField");
        const postRadios = document.getElementsByName("post");

        postRadios.forEach(radio => {
            radio.addEventListener("change", function () {
                if (otherPostRadio.checked) {
                    otherPostField.style.display = "block";
                } else {
                    otherPostField.style.display = "none";
                }
            });
        });
    });

    document.querySelectorAll("input[name='post']").forEach((elem) => {
        elem.addEventListener("change", function () {
            document.getElementById("otherPostField").style.display = this.value === "Other" ? "block" : "none";
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        let myModal = new bootstrap.Modal(document.getElementById("myModal"));
        myModal.show();  // Page load hone par modal open hoga
    });
});



    document.addEventListener("DOMContentLoaded", function () {
        let myModal = new bootstrap.Modal(document.getElementById("myModal"));
        myModal.show();  // Page load hone par modal open hoga
    });
