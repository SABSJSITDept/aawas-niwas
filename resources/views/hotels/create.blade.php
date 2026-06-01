@extends('admin.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-dark rounded-top-4 py-3 px-4">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-building"></i>🏨 Hotel Registration
                    </h3>
                </div>

                <div class="card-body bg-white rounded-bottom-4 px-4 py-5">

                    <!-- Form starts -->
                    <form id="hotelForm" action="{{ route('hotel.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">🏨 Hotel Name</label>
                                <input type="text" name="hotel_name" class="form-control rounded-3 shadow-sm" placeholder="e.g. Hotel Sunrise" required value="{{ old('hotel_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">👤 Incharge Person</label>
                                <input type="text" name="incharge_name" class="form-control rounded-3 shadow-sm" placeholder="e.g. Ramesh Kumar" required value="{{ old('incharge_name') }}">
                            </div>

                            <div class="col-md-6">
  <label class="form-label fw-semibold">📞 Contact Number</label>
  <input
    type="text"
    name="contact_number"
    id="contact_number"
    class="form-control rounded-3 shadow-sm"
    placeholder="e.g. 9876543210"
    required
    value="{{ old('contact_number') }}"
    inputmode="numeric"
    pattern="\d{10}"
    maxlength="10"
    title="Please enter up to 10 digits"
  >
</div>


                            <!-- Total Rooms -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">🏨 Total Rooms</label>
                                <input type="number" name="total_rooms" class="form-control rounded-3 shadow-sm" placeholder="e.g. 50" required value="{{ old('total_rooms') }}">
                            </div>

                            <!-- Common Bathroom -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">🚿 Common Bathroom</label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="common_bath" id="commonYes" value="Yes" {{ old('common_bath') == 'Yes' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary" for="commonYes">Yes</label>

                                    <input type="radio" class="btn-check" name="common_bath" id="commonNo" value="No" {{ old('common_bath') == 'No' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary" for="commonNo">No</label>
                                </div>
                            </div>

                            <!-- Lift -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">🛗 Lift Facility</label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="lift" id="liftYes" value="Yes" {{ old('lift') == 'Yes' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary" for="liftYes">Yes</label>

                                    <input type="radio" class="btn-check" name="lift" id="liftNo" value="No" {{ old('lift') == 'No' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary" for="liftNo">No</label>
                                </div>
                            </div>

                            <!-- Generator -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">⚡ Generator Facility</label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="generator" id="generatorYes" value="Yes" {{ old('generator') == 'Yes' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary" for="generatorYes">Yes</label>

                                    <input type="radio" class="btn-check" name="generator" id="generatorNo" value="No" {{ old('generator') == 'No' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary" for="generatorNo">No</label>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">📍 Full Address</label>
                                <textarea name="address" class="form-control rounded-3 shadow-sm" rows="3" placeholder="e.g. Near Bus Stand, Bikaner, Rajasthan" required>{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Google Maps Link -->
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-semibold">🗺️ Google Maps Link</label>
                            <input type="url" name="google_maps_link" class="form-control rounded-3 shadow-sm" placeholder="https://www.google.com/maps?q=..." value="{{ old('google_maps_link') }}">
                        </div>

                        <div class="d-grid mt-5">
                            <button id="submitBtn" type="submit" class="btn btn-success rounded-3 py-2 fs-5 shadow">
                                <i class="bi bi-send-check"></i> Submit Hotel Info
                            </button>
                        </div>

                    </form>
                    <!-- Form ends -->

                </div>
            </div>

        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  // Read flash data (works whether window.__FLASH__ set by Blade or not)
  const flash = window.__FLASH__ || {
    success: null,
    error: null,
    errors: null
  };

  // Utility to safely show a SweetAlert message
  function showSwal(opts) {
    // opts: { title, text, html, icon, timer, showConfirmButton }
    return Swal.fire(Object.assign({
      title: opts.title || '',
      text: opts.text || undefined,
      html: opts.html || undefined,
      icon: opts.icon || undefined,
      confirmButtonText: opts.confirmButtonText || 'OK',
      timer: opts.timer || undefined,
      showConfirmButton: (typeof opts.showConfirmButton === 'boolean') ? opts.showConfirmButton : true,
      allowOutsideClick: (typeof opts.allowOutsideClick === 'boolean') ? opts.allowOutsideClick : true
    }, opts.extra || {}));
  }

  // Show any flash messages present
  document.addEventListener('DOMContentLoaded', function () {
    try {
      if (flash.success) {
        showSwal({ title: 'Success', text: String(flash.success), icon: 'success' });
      }
      if (flash.error) {
        showSwal({ title: 'Error', text: String(flash.error), icon: 'error' });
      }
      if (Array.isArray(flash.errors) && flash.errors.length) {
        const html = flash.errors.map(e => '- ' + String(e)).join('<br>');
        showSwal({ title: 'Please fix the following', html: html, icon: 'warning' });
      }
    } catch (err) {
      // safe fallback: do nothing if flash parsing fails
      console.warn('Flash handling error', err);
    }
  });

  // Form submit confirmation logic
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('hotelForm');
    const submitBtn = document.getElementById('submitBtn');

    if (!form || !submitBtn) return; // nothing to do if elements missing

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // always prevent immediate submit

      // If form has HTML5 validation, check it first
      if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
        // Let browser show validation UI
        form.reportValidity();
        return;
      }

      Swal.fire({
        title: 'Are you sure?',
        text: "Once submitted you won't be able to edit from this screen easily.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          // disable button to prevent double submit
          try {
            submitBtn.disabled = true;
            submitBtn.dataset.originalHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
          } catch (err) { /* ignore DOM errors */ }

          // Show loading alert and submit form
          Swal.fire({
            title: 'Submitting...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
              // small timeout to ensure loading UI renders before submit
              setTimeout(() => {
                form.submit();
              }, 250);
            }
          });
        } else {
          // cancelled
          Swal.fire({
            title: 'Cancelled',
            text: 'Submission was cancelled.',
            icon: 'info',
            timer: 1200,
            showConfirmButton: false
          });
        }
      });
    });
  });
})();
</script>
@endsection
