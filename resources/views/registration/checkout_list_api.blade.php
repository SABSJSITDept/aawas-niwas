@extends('admin.layout')
@section('title', 'Check-out Registrations (API)')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
* {
    font-family: 'Inter', sans-serif;
}
body {
    background: #f5f7fa;
}
.page-header {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8a80 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 15px 35px rgba(255, 107, 107, 0.3);
    position: relative;
    box-shadow: 0 15px 35px rgba(255, 107, 107, 0.3);
    position: relative;
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
    min-height: 500px;
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
.status-checkout {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: #d11a2a;
}
.table-responsive {
    max-height: 65vh;
    min-height: 400px;
    overflow: auto;
}
.table-responsive thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #fff;
}
.table-responsive th:last-child,
.table-responsive td:last-child {
    position: sticky;
    right: 0;
    z-index: 11;
    background: #fff;
    box-shadow: -3px 0 5px rgba(0,0,0,0.05);
}
.table-responsive thead th:last-child {
    z-index: 12;
    background: #fff;
}
.modern-table tbody tr:hover td:last-child {
    background: #fbfbfe;
}
</style>

<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-sign-out-alt me-2"></i>Check-out Registrations</h2>
                <p class="subtitle mb-0">View all successfully checked-out bookings</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-light btn-sm" onclick="clearFilters()" title="Clear Filters">
                    <i class="fas fa-times-circle me-1"></i> Clear Filters
                </button>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-columns me-1"></i> Columns
                    </button>
                    <ul class="dropdown-menu p-2 shadow-sm" aria-labelledby="columnsDropdown" id="column-toggles" style="max-height: 400px; overflow-y: auto;">
                        <!-- dynamically populated -->
                    </ul>
                </div>
                <button type="button" class="btn btn-light btn-sm" onclick="exportCheckoutData('excel')" title="Export to Excel">
                    <i class="fas fa-file-excel me-1 text-success"></i>Excel
                </button>
                <button type="button" class="btn btn-light btn-sm" onclick="exportCheckoutData('pdf')" title="Export to PDF">
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
        <div id="pagination-container" class="p-3 border-top bg-white"></div>
    </div>
</div>

<script>
const API_URL = '/api/registration/checkout-list';
let currentPage = 1;

const DB_COLUMNS = [
    { key: 'index', label: '#', defaultVisible: true, filterable: false },
    { key: 'booking_id', exportKey: 'booking_id', label: 'Booking ID', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'name', exportKey: 'name', label: 'Name', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'father_name', exportKey: 'father_name', label: 'Father Name', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'phone', exportKey: 'phone', label: 'Phone', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'type', exportKey: 'type', label: 'Type', defaultVisible: true, filterable: true, type: 'select', options: ['family', 'group'] },
    { key: 'aadhar_number', exportKey: 'aadhar_number', label: 'Aadhar', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'age', exportKey: 'age', label: 'Age', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'gender', exportKey: 'gender', label: 'Gender', defaultVisible: false, filterable: true, type: 'select', options: ['Male', 'Female'] },
    { key: 'ms_name', exportKey: 'ms_name', label: 'MS Name', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'mid', exportKey: 'mid', label: 'MID', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'city', exportKey: 'city', label: 'City', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'state', exportKey: 'state', label: 'State', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'aanchal', exportKey: 'aanchal', label: 'Aanchal', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'travel_type', exportKey: 'travel_type', label: 'Travel Type', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'check_in_date', exportKey: 'check_in_date', label: 'Check-In Date', defaultVisible: true, filterable: true, type: 'date' },
    { key: 'check_in_time', exportKey: 'check_in_time', label: 'Check-In Time', defaultVisible: false, filterable: true, type: 'time' },
    { key: 'check_out_date', exportKey: 'check_out_date', label: 'Check-Out Date', defaultVisible: true, filterable: true, type: 'date' },
    { key: 'check_out_time', exportKey: 'check_out_time', label: 'Check-Out Time', defaultVisible: false, filterable: true, type: 'time' },
    { key: 'total_persons', exportKey: 'total_persons', label: 'Total Persons', defaultVisible: true, filterable: true, type: 'text' },
    { key: 'family_coming', exportKey: 'family_coming', label: 'Family Coming', defaultVisible: false, filterable: true, type: 'select', options: ['yes', 'no'] },
    { key: 'no_of_people', exportKey: 'no_of_people', label: 'No of People', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'no_of_children', exportKey: 'no_of_children', label: 'No of Children', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'total_male', exportKey: 'total_male', label: 'Total Male', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'total_female', exportKey: 'total_female', label: 'Total Female', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'sixty_plus_members', exportKey: 'sixty_plus_members', label: '60+ Members', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'sixty_plus_male', exportKey: 'sixty_plus_male', label: '60+ Male', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'sixty_plus_female', exportKey: 'sixty_plus_female', label: '60+ Female', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'is_veer_parivar', exportKey: 'is_veer_parivar', label: 'Veer Parivar', defaultVisible: false, filterable: true, type: 'select', options: ['yes', 'no'] },
    { key: 'remark', exportKey: 'remark', label: 'Remark', defaultVisible: false, filterable: true, type: 'text' },
    { key: 'status', label: 'Status', defaultVisible: true, filterable: false }
];

function initDynamicHeaders() {
    let dropdownHtml = '';
    let headersHtml = '';

    DB_COLUMNS.forEach((col, idx) => {
        const checked = col.defaultVisible ? 'checked' : '';
        const exportAttr = col.exportKey ? `data-export-key="${col.exportKey}"` : '';
        
        dropdownHtml += `<li><label class="dropdown-item"><input type="checkbox" class="col-toggle" value="${idx}" ${exportAttr} ${checked}> ${col.label}</label></li>`;
        
        let filterHtml = '';
        if (col.filterable) {
            let inputElement = '';
            if (col.type === 'select') {
                const optionsHtml = col.options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                inputElement = `<select class="form-select form-select-sm column-filter" id="filter_${col.key}"><option value="">All</option>${optionsHtml}</select>`;
            } else {
                inputElement = `<input type="${col.type}" class="form-control form-control-sm column-filter" id="filter_${col.key}" placeholder="Search...">`;
            }

            filterHtml = `
            <div class="dropdown d-inline-block">
                <button class="btn btn-sm btn-link p-0 text-muted ms-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu p-2 shadow" style="min-width: 200px;">
                    ${inputElement}
                </div>
            </div>`;
        }

        const style = col.defaultVisible ? '' : 'display: none;';
        headersHtml += `<th style="${style}" data-col-idx="${idx}" class="text-nowrap">${col.label} ${filterHtml}</th>`;
    });

    document.getElementById('column-toggles').innerHTML = dropdownHtml;
}

function fetchData() {
    const params = new URLSearchParams();
    params.append('page', currentPage);
    params.append('per_page', 25);
    
    DB_COLUMNS.forEach(col => {
        if (col.filterable) {
            const val = document.getElementById(`filter_${col.key}`)?.value?.trim();
            if (val) {
                params.append(`filter_${col.key}`, val);
            }
        }
    });

    fetch(API_URL + '?' + params.toString())
        .then(res => res.json())
        .then(res => {
            renderTable(res.data || []);
            if (res.meta) {
                renderPagination(res.meta, '#pagination-container');
            }
        });
}

function renderTable(data) {
    let headersHtml = '<tr>';
    DB_COLUMNS.forEach((col, idx) => {
        const style = col.defaultVisible ? '' : 'display: none;';
        let filterHtml = '';
        if (col.filterable) {
            let inputElement = '';
            if (col.type === 'select') {
                const optionsHtml = col.options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                const currentVal = document.getElementById(`filter_${col.key}`)?.value || '';
                inputElement = `<select class="form-select form-select-sm column-filter" id="filter_${col.key}">
                    <option value="" ${currentVal === '' ? 'selected' : ''}>All</option>
                    ${col.options.map(opt => `<option value="${opt}" ${currentVal === opt ? 'selected' : ''}>${opt}</option>`).join('')}
                </select>`;
            } else {
                const currentVal = document.getElementById(`filter_${col.key}`)?.value || '';
                inputElement = `<input type="${col.type}" class="form-control form-control-sm column-filter" id="filter_${col.key}" placeholder="Search..." value="${currentVal}">`;
            }

            filterHtml = `
            <div class="dropdown d-inline-block">
                <button class="btn btn-sm btn-link p-0 text-muted ms-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu p-2 shadow" style="min-width: 200px;">
                    ${inputElement}
                </div>
            </div>`;
        }
        headersHtml += `<th style="${style}" data-col-idx="${idx}" class="text-nowrap">${col.label} ${filterHtml}</th>`;
    });
    headersHtml += '</tr>';

    let html = `<div class="table-responsive"><table class='modern-table' id='data-table'><thead>${headersHtml}</thead><tbody>`;
    
    data.forEach((row, i) => {
        if(row.status === 'check-out') {
            html += '<tr>';
            DB_COLUMNS.forEach((col, idx) => {
                const style = col.defaultVisible ? '' : 'display: none;';
                let val = '';
                if (col.key === 'index') val = ((currentPage - 1) * 25) + i + 1;
                else if (col.key === 'status') val = `<span class="status-badge status-checkout">Check-out</span>`;
                else if (col.key === 'booking_id') val = `<strong>${row.display_id || row.id}</strong>`;
                else if (col.key === 'total_persons') val = `<strong>${row.total_persons ?? '-'}</strong>`;
                else if (col.key === 'city') val = row.city_name ?? row.city ?? '';
                else if (col.key === 'state') val = row.state_name ?? row.state ?? '';
                else if (col.key === 'aanchal') val = row.aanchal_name ?? row.aanchal ?? '';
                else val = row[col.key] ?? '-';
                
                html += `<td style="${style}" data-col-idx="${idx}">${val}</td>`;
            });
            html += '</tr>';
        }
    });
    html += '</tbody></table></div>';
    document.getElementById('api-table-container').innerHTML = html;
    
    // Rebind filter events
    document.querySelectorAll('.column-filter').forEach(el => {
        if(el.tagName === 'INPUT' && (el.type === 'text' || el.type === 'number')) {
            el.addEventListener('keypress', function(e) {
                if(e.key === 'Enter') {
                    currentPage = 1;
                    fetchData();
                }
            });
        } else {
            el.addEventListener('change', function() {
                currentPage = 1;
                fetchData();
            });
        }
    });

    applyColumnVisibility();
}

function applyColumnVisibility() {
    document.querySelectorAll('.col-toggle').forEach(checkbox => {
        const colIdx = checkbox.value;
        const isChecked = checkbox.checked;
        const table = document.getElementById('data-table');
        if (table) {
            // thead
            table.querySelectorAll('thead tr').forEach(row => {
                const ths = row.querySelectorAll('th');
                if (ths[colIdx]) ths[colIdx].style.display = isChecked ? '' : 'none';
            });
            // tbody
            table.querySelectorAll('tbody tr').forEach(row => {
                const tds = row.querySelectorAll('td');
                if (tds[colIdx]) tds[colIdx].style.display = isChecked ? '' : 'none';
            });
        }
    });
}

function clearFilters() {
    document.querySelectorAll('.column-filter').forEach(el => el.value = '');
    currentPage = 1;
    fetchData();
}

function renderPagination(meta, containerId) {
    const container = document.querySelector(containerId);
    if (!meta || meta.total === 0) {
        container.innerHTML = '';
        return;
    }
    let html = `
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small">
            Showing ${((meta.current_page - 1) * meta.per_page) + 1} to ${Math.min(meta.current_page * meta.per_page, meta.total)} of ${meta.total} entries
        </div>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${meta.current_page - 1}); return false;">Previous</a>
            </li>
            <li class="page-item disabled"><a class="page-link" href="#">Page ${meta.current_page} of ${meta.last_page}</a></li>
            <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${meta.current_page + 1}); return false;">Next</a>
            </li>
        </ul>
    </div>`;
    container.innerHTML = html;
}

window.changePage = function(page) {
    currentPage = page;
    fetchData();
};

document.addEventListener('DOMContentLoaded', () => {
    initDynamicHeaders();
    fetchData();
    document.querySelectorAll('.col-toggle').forEach(el => {
        el.addEventListener('change', applyColumnVisibility);
    });
    // Bootstrap dropdown keeping open logic
    document.getElementById('column-toggles').addEventListener('click', function (e) {
        e.stopPropagation();
    });
});

// Export functionality for checkout registrations
async function exportCheckoutData(format) {
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
        
        const params = new URLSearchParams();
        params.append('format', format);
        
        DB_COLUMNS.forEach(col => {
            if (col.filterable) {
                const val = document.getElementById(`filter_${col.key}`)?.value?.trim();
                if (val) {
                    params.append(`filter_${col.key}`, val);
                }
            }
        });

        document.querySelectorAll('.col-toggle:checked').forEach(checkbox => {
            const key = checkbox.getAttribute('data-export-key');
            if (key) {
                params.append('visible_columns[]', key);
            }
        });

        const exportUrl = `/api/registration/checkout-export?${params.toString()}`;
        
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
        link.download = `checkout-registrations-${new Date().toISOString().slice(0,10)}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
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

</script>
@endsection
