@extends('admin.layout')

@section('content')
<div class="container">
  <!-- Row: heading + button on same line (responsive) -->
  <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-4">
    <!-- Heading (keeps bold and primary color) -->
    <h2 class="mb-2 mb-sm-0 text-primary fw-bold">🏨 Hotels List</h2>

    <!-- Button (next to heading on wide screens; stacked on very small) -->
    <a href="{{ route('hotel.create') }}"
       class="btn btn-success btn-lg shadow-sm ms-0 ms-sm-3"
       role="button"
       aria-label="Add Hotel">
      <i class="fas fa-plus me-1"></i> Add Hotel
    </a>
  </div>

    {{-- Font Awesome CDN --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <div class="row">
        @foreach($hotels as $hotel)
        <div class="col-lg-4 col-md-6">
            <div class="card hotel-card shadow-lg border-0 rounded-lg mb-4">

                {{-- Action Bar --}}
                <div class="card-action-bar d-flex justify-content-end p-2">
                    {{-- Edit --}}
                    <a href="{{ route('hotel.edit', $hotel->id) }}" class="btn-icon edit-btn" title="Edit Hotel">
                        <i class="fas fa-pen-to-square"></i>
                    </a>

                    {{-- Delete (use class confirm-action and data attributes for SweetAlert) --}}
                    <form action="{{ route('hotel.destroy', $hotel->id) }}" method="POST" class="ms-2 confirm-action"
                          data-confirm-title="Delete Hotel?"
                          data-confirm-text="Are you sure you want to delete this hotel? This action cannot be undone."
                          data-confirm-button="Yes, delete"
                          data-loading-text="Deleting...">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon delete-btn" title="Delete Hotel">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                    {{-- Status Toggle --}}
                    <form action="{{ route('hotel.toggleStatus', $hotel->id) }}" method="POST" class="ms-2 confirm-action"
                          data-confirm-title="Change Status?"
                          data-confirm-text="{{ $hotel->status === 'active' ? 'Do you want to deactivate this hotel?' : 'Do you want to activate this hotel?' }}"
                          data-confirm-button="{{ $hotel->status === 'active' ? 'Yes, deactivate' : 'Yes, activate' }}"
                          data-loading-text="{{ $hotel->status === 'active' ? 'Deactivating...' : 'Activating...' }}">
                        @csrf
                        <button type="submit" class="btn-icon {{ $hotel->status === 'active' ? 'status-active-btn' : 'status-inactive-btn' }}" title="Toggle Status">
                            <i class="fas {{ $hotel->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                        </button>
                    </form>
                </div>

                <div class="card-body pt-2">
                    <h4 class="hotel-name text-dark fw-bold mb-2">{{ $hotel->hotel_name }}</h4>
                    <p class="text-muted"><i class="fas fa-user-tie me-1"></i> <b>Incharge:</b> {{ $hotel->incharge_name }}</p>
                    <p class="text-muted"><i class="fas fa-phone me-1"></i> <b>Contact:</b> {{ $hotel->contact_number }}</p>
                    <p class="text-muted"><i class="fas fa-bed me-1"></i> <b>Total Rooms:</b> {{ $hotel->total_rooms }}</p>
                    <p class="text-muted"><i class="fas fa-location-dot me-1"></i> {{ $hotel->address }}</p>

                    <a href="{{ route('hotel.actions', $hotel->id) }}" class="btn btn-outline-primary w-100 mt-2">
                        <i class="fas fa-gear me-1"></i> Manage Hotel
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<style>
    .hotel-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 5px solid #007bff;
        position: relative;
    }

    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }

    .hotel-name {
        font-size: 1.4rem;
    }

    .card-action-bar {
        background-color: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid #eee;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        border: none;
    }

    .edit-btn {
        background-color: #ffc107;
        color: #fff;
    }

    .edit-btn:hover {
        background-color: #e0a800;
        transform: scale(1.1);
    }

    .delete-btn {
        background-color: #dc3545;
        color: #fff;
    }

    .delete-btn:hover {
        background-color: #bb2d3b;
        transform: scale(1.1);
    }

    .status-active-btn {
        background-color: #28a745;
        color: #fff;
    }

    .status-active-btn:hover {
        background-color: #218838;
        transform: scale(1.1);
    }

    .status-inactive-btn {
        background-color: #6c757d;
        color: #fff;
    }

    .status-inactive-btn:hover {
        background-color: #5a6268;
        transform: scale(1.1);
    }

    .me-1 {
        margin-right: 0.4rem;
    }

    .ms-2 {
        margin-left: 0.5rem;
    }
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Pass Laravel flash/errors safely to JS -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const flash = window.__FLASH__ || {};

    // Show any server-side flash messages after redirect
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

    // Attach confirmation handler to forms with class .confirm-action
    document.querySelectorAll('form.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const title = form.dataset.confirmTitle || 'Are you sure?';
            const text = form.dataset.confirmText || '';
            const confirmButtonText = form.dataset.confirmButton || 'Yes';
            const loadingText = form.dataset.loadingText || 'Processing...';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(result => {
                if (result.isConfirmed) {
                    // Disable all buttons inside the form to prevent double submit
                    Array.from(form.querySelectorAll('button, input[type="submit"]')).forEach(el => el.disabled = true);

                    // Show loading alert then submit
                    Swal.fire({
                        title: loadingText,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            // small timeout so loading indicator renders
                            setTimeout(() => form.submit(), 200);
                        }
                    });
                } else {
                    // optionally show cancelled toast
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'Action was cancelled.',
                        icon: 'info',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
});
</script>
@endsection
