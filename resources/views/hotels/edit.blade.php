    @extends('admin.layout')

    @section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-dark rounded-top-4 py-3 px-4">
            <h3 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i> ✏️ Edit Hotel
            </h3>
            </div>

            <div class="card-body bg-white rounded-bottom-4 px-4 py-5">
            <!-- Form -->
            <form id="hotelEditForm" action="{{ route('hotel.update', $hotel->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">🏨 Hotel Name</label>
                    <input type="text" name="hotel_name" class="form-control rounded-3 shadow-sm" placeholder="e.g. Hotel Sunrise" required
                        value="{{ old('hotel_name', $hotel->hotel_name) }}">
                    @error('hotel_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">👤 Incharge Name</label>
                    <input type="text" name="incharge_name" class="form-control rounded-3 shadow-sm" placeholder="e.g. Ramesh Kumar" required
                        value="{{ old('incharge_name', $hotel->incharge_name) }}">
                    @error('incharge_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">📞 Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" class="form-control rounded-3 shadow-sm"
                        placeholder="e.g. 9876543210" required maxlength="10" inputmode="numeric" pattern="\d{10}"
                        value="{{ old('contact_number', $hotel->contact_number) }}">
                    @error('contact_number')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text small text-muted">Only digits. Max 10 characters.</div>
                </div>

                <!-- Additional Contacts Dynamic Section -->
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold">👥 Additional Contact Persons (Optional)</label>
                    <div id="additionalContactsContainer">
                        @if(!empty($hotel->additional_contacts) && is_array($hotel->additional_contacts))
                            @foreach($hotel->additional_contacts as $contact)
                                <div class="row g-2 mb-2 align-items-center contact-row">
                                    <div class="col-md-5">
                                        <input type="text" name="additional_contact_name[]" class="form-control rounded-3 shadow-sm" placeholder="Contact Name" required value="{{ $contact['name'] ?? '' }}">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="additional_contact_phone[]" class="form-control rounded-3 shadow-sm" placeholder="Contact Number (10 digits)" required inputmode="numeric" pattern="\d{10}" maxlength="10" value="{{ $contact['phone'] ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-contact-btn" title="Remove Contact">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addContactBtn">
                        <i class="bi bi-plus-circle"></i> Add Another Contact
                    </button>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">🏨 Total Rooms</label>
                    <input type="number" name="total_rooms" class="form-control rounded-3 shadow-sm" placeholder="e.g. 50" required min="1"
                        value="{{ old('total_rooms', $hotel->total_rooms) }}">
                    @error('total_rooms')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Common Bathroom (yes/no) -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold d-block">🚿 Common Bathroom</label>
                    <div class="btn-group" role="group" aria-label="common bath">
                    <input type="radio" class="btn-check" name="common_bath" id="common_yes" value="yes"
                            {{ old('common_bath', $hotel->common_bath) == 'yes' ? 'checked' : '' }} required>
                    <label class="btn btn-outline-primary" for="common_yes">Yes</label>

                    <input type="radio" class="btn-check" name="common_bath" id="common_no" value="no"
                            {{ old('common_bath', $hotel->common_bath) == 'no' ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="common_no">No</label>
                    </div>
                    @error('common_bath')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lift -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold d-block">🛗 Lift Facility</label>
                    <div class="btn-group" role="group" aria-label="lift">
                    <input type="radio" class="btn-check" name="lift" id="lift_yes" value="yes"
                            {{ old('lift', $hotel->lift) == 'yes' ? 'checked' : '' }} required>
                    <label class="btn btn-outline-primary" for="lift_yes">Yes</label>

                    <input type="radio" class="btn-check" name="lift" id="lift_no" value="no"
                            {{ old('lift', $hotel->lift) == 'no' ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="lift_no">No</label>
                    </div>
                    @error('lift')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Generator -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold d-block">⚡ Generator Facility</label>
                    <div class="btn-group" role="group" aria-label="generator">
                    <input type="radio" class="btn-check" name="generator" id="gen_yes" value="yes"
                            {{ old('generator', $hotel->generator) == 'yes' ? 'checked' : '' }} required>
                    <label class="btn btn-outline-primary" for="gen_yes">Yes</label>

                    <input type="radio" class="btn-check" name="generator" id="gen_no" value="no"
                            {{ old('generator', $hotel->generator) == 'no' ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="gen_no">No</label>
                    </div>
                    @error('generator')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">📍 Full Address</label>
                    <textarea name="address" class="form-control rounded-3 shadow-sm" rows="3" placeholder="e.g. Near Bus Stand, Bikaner, Rajasthan" required>{{ old('address', $hotel->address) }}</textarea>
                    @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Google Maps Link -->
                <div class="col-md-12 mt-3">
                    <label class="form-label fw-semibold">🗺️ Google Maps Link</label>
                    <input type="url" name="google_maps_link" class="form-control rounded-3 shadow-sm"
                        placeholder="https://www.google.com/maps?q=..." value="{{ old('google_maps_link', $hotel->google_maps_link) }}">
                    @error('google_maps_link')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                </div>

                <div class="d-grid mt-5">
                <button id="submitEditBtn" type="submit" class="btn btn-primary rounded-3 py-2 fs-5 shadow">
                    <i class="bi bi-save"></i> Update Hotel
                </button>
                </div>
            </form>
            <!-- /Form -->
            </div>
        </div>

        </div>
    </div>
    </div>

    <!-- Pass Laravel flash/errors to JS safely -->


    <!-- Script: contact input limiter + SweetAlert confirm + flash handling -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Contact input: allow digits only, max 10
    const contactInput = document.getElementById('contact_number');
    if (contactInput) {
        contactInput.addEventListener('input', function () {
        let cleaned = this.value.replace(/\D/g, '');
        if (cleaned.length > 10) cleaned = cleaned.slice(0, 10);
        if (this.value !== cleaned) this.value = cleaned;
        });

        contactInput.addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (!/^\d+$/.test(pasted)) {
            e.preventDefault();
            return;
        }
        if (pasted.length + this.value.length > 10) {
            e.preventDefault();
            const toInsert = pasted.slice(0, 10 - this.value.length);
            const start = this.selectionStart || this.value.length;
            const end = this.selectionEnd || start;
            this.value = this.value.slice(0, start) + toInsert + this.value.slice(end);
        }
        });
    }

    // SweetAlert submit confirmation
    const form = document.getElementById('hotelEditForm');
    const submitBtn = document.getElementById('submitEditBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function (e) {
        e.preventDefault();

        // check native validity first
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this hotel's details?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
            // disable and show loader
            submitBtn.disabled = true;
            submitBtn.dataset.original = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

            Swal.fire({
                title: 'Updating...',
                allowOutsideClick: false,
                didOpen: () => {
                Swal.showLoading();
                setTimeout(() => form.submit(), 200);
                }
            });
            } else {
            Swal.fire({
                title: 'Cancelled',
                text: 'Update cancelled.',
                icon: 'info',
                timer: 1200,
                showConfirmButton: false
            });
            }
        });
        });
    }

    // Show flash messages from server (via window.__FLASH__)
    const flash = window.__FLASH__ || {};
    try {
        if (flash.success) {
        Swal.fire({ title: 'Success', text: String(flash.success), icon: 'success', confirmButtonText: 'OK' });
        }
        if (flash.error) {
        Swal.fire({ title: 'Error', text: String(flash.error), icon: 'error', confirmButtonText: 'OK' });
        }
        if (Array.isArray(flash.errors) && flash.errors.length) {
        const html = flash.errors.map(e => '- ' + String(e)).join('<br>');
        Swal.fire({ title: 'Please fix the following', html: html, icon: 'warning', confirmButtonText: 'OK' });
        }
    } catch (err) {
        console.warn('Flash parse error', err);
    }

    // Dynamic Additional Contacts logic for edit form
    const container = document.getElementById('additionalContactsContainer');
    const addBtn = document.getElementById('addContactBtn');

    if (container) {
        // Attach event listeners to existing remove buttons
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-contact-btn')) {
                e.target.closest('.contact-row').remove();
            }
        });
    }

    if (addBtn && container) {
      addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-center contact-row';
        row.innerHTML = `
          <div class="col-md-5">
              <input type="text" name="additional_contact_name[]" class="form-control rounded-3 shadow-sm" placeholder="Contact Name" required>
          </div>
          <div class="col-md-5">
              <input type="text" name="additional_contact_phone[]" class="form-control rounded-3 shadow-sm" placeholder="Contact Number (10 digits)" required inputmode="numeric" pattern="\\d{10}" maxlength="10">
          </div>
          <div class="col-md-2">
              <button type="button" class="btn btn-outline-danger btn-sm remove-contact-btn" title="Remove Contact">
                  <i class="bi bi-trash"></i>
              </button>
          </div>
        `;
        container.appendChild(row);
      });
    }

    });
    </script>

    @endsection
