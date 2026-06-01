@extends('admin.layout')

@section('title', 'Completed Registrations (API)')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
* {
    font-family: 'Inter', sans-serif;
}
body {
    background: #f5f7fa;
}
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.page-header h2 {
    margin: 0;
    font-weight: 800;
    font-size: 2.2rem;
    letter-spacing: -0.5px;
}
.page-header .subtitle {
    opacity: 0.95;
    margin-top: 0.5rem;
    font-weight: 400;
    font-size: 1.05rem;
}
.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 1.5rem;
    overflow-x: auto;
}
.modern-table {
    width: 100%;
    border-collapse: collapse;
}
.modern-table thead th {
    font-weight: 700;
    font-size: 0.9rem;
    color: #495057;
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid #eef2f7;
}
.modern-table tbody td {
    padding: 0.9rem;
    vertical-align: middle;
    border-bottom: 1px solid #f4f6fa;
}
.modern-table tbody tr:hover {
    background: #fbfbfe;
    transform: translateX(5px);
    transition: all 0.3s ease;
}
.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-block;
}
.status-completed {
    background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
    color: white;
}
</style>

<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-check-circle me-2"></i>Completed Registrations</h2>
                <p class="subtitle mb-0">View all successfully completed bookings</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light btn-sm" onclick="exportCompletedData('excel')" title="Export to Excel">
                    <i class="fas fa-file-excel me-1 text-success"></i>Excel
                </button>
                <button type="button" class="btn btn-light btn-sm" onclick="exportCompletedData('pdf')" title="Export to PDF">
                    <i class="fas fa-file-pdf me-1 text-danger"></i>PDF
                </button>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div id="api-table-container">
            <div class="text-center py-5">
                <span class="spinner-border"></span> Loading data...
            </div>
        </div>
    </div>
</div>

<script>
const API_URL = '/api/registration/completed-list';

function fetchData() {
    fetch(API_URL)
        .then(res => res.json())
        .then(res => renderTable(res.data || []));
}

function renderTable(data) {
    let html = `<table class='modern-table'><thead><tr>
        <th><i class="fas fa-hashtag me-2"></i>Booking ID</th>
        <th><i class="fas fa-user me-2"></i>Name</th>
        <th><i class="fas fa-phone me-2"></i>Phone</th>
        <th><i class="fas fa-users me-2"></i>Total Persons</th>
        <th><i class="fas fa-calendar-check me-2"></i>Check-In</th>
        <th><i class="fas fa-calendar-times me-2"></i>Check-Out</th>
        <th><i class="fas fa-tag me-2"></i>Type</th>
        <th><i class="fas fa-info-circle me-2"></i>Status</th>
        <th><i class="fas fa-sign-out-alt me-2"></i>Action</th>
    </tr></thead><tbody>`;
    data.forEach(row => {
        if(row.status === 'completed') {
            html += `<tr>
                <td><strong>${row.display_id || row.id}</strong></td>
                <td>${row.name}</td>
                <td>${row.phone}</td>
                <td><strong>${row.total_persons ?? '-'}</strong></td>
                <td>${row.check_in_date ?? '-'}</td>
                <td>${row.check_out_date ?? '-'}</td>
                <td>${row.type}</td>
                <td><span class="status-badge status-completed">Completed</span></td>
                <td><button class="btn btn-danger btn-sm" onclick="checkoutBooking('${row.type}', ${row.id})">Check Out</button></td>
            </tr>`;
        }
    });
    html += '</tbody></table>';
    document.getElementById('api-table-container').innerHTML = html;
}

function checkoutBooking(type, id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will check out the booking and remove related entries.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, check out!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/registration/${type}/${id}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    Swal.fire('Checked Out!', 'The booking has been checked out.', 'success');
                    fetchData(); // Refresh the table
                } else {
                    Swal.fire('Error!', response.message || 'Something went wrong.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Unable to process the request.', 'error');
            });
        }
    });
}

// Export functionality for completed registrations
async function exportCompletedData(format) {
    try {
        // Show loading message
        const loadingMessage = format === 'excel' ? 'Preparing Excel file...' : 'Generating PDF...';
        Swal.fire({
            title: 'Exporting Data',
            text: loadingMessage,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Make API request to get the file
        const exportUrl = `/api/registration/completed-export?format=${format}`;
        
        const response = await fetch(exportUrl, {
            method: 'GET',
            headers: {
                'Accept': format === 'excel' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/pdf',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }
        
        // Get the blob from response
        const blob = await response.blob();
        
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `completed-registrations-${new Date().toISOString().slice(0,10)}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up the URL object
        window.URL.revokeObjectURL(url);
        
        // Close loading message and show success
        Swal.close();
        Swal.fire({
            title: 'Export Complete',
            text: `${format.toUpperCase()} file has been downloaded successfully!`,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        
    } catch (error) {
        console.error('Export error:', error);
        Swal.close();
        Swal.fire({
            title: 'Export Failed',
            text: `Failed to export ${format.toUpperCase()} file. Please try again.`,
            icon: 'error'
        });
    }
}

document.addEventListener('DOMContentLoaded', fetchData);
</script>
@endsection
