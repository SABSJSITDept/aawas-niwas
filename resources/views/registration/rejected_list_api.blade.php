@extends('admin.layout')
@section('title', 'Rejected Registrations')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root{
  --accent-1: #6b5bff; /* primary gradient start */
  --accent-2: #8f6bff; /* primary gradient end */
  --muted: #6c757d;
  --card-radius: 12px;
}
*{font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;}
body{background:#f4f6fb;padding:1.5rem 0}
.header-row{display:flex;gap:1rem;align-items:center;justify-content:space-between;margin-bottom:1rem}
.page-title{display:flex;gap:.8rem;align-items:center}
.page-title h3{margin:0;font-weight:700;font-size:1.15rem}
.page-sub{color:var(--muted);font-size:.92rem}

.stats-grid{display:flex;gap:1rem;align-items:center}
.stat-card{background:linear-gradient(90deg,var(--accent-1),var(--accent-2));color:#fff;padding:.35rem .65rem;border-radius:4px;min-width:120px;box-shadow:0 3px 10px rgba(111,85,255,0.12)}
.stat-card small{display:block;opacity:.75;font-size:.7rem}
.stat-card h4{margin:0;font-size:1rem;font-weight:500}

.card-compact{border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(20,20,50,0.06);overflow:hidden}
.search-row{display:flex;gap:1rem;align-items:center}
.input-search{flex:1}

.table-responsive{background:#fff;border-radius:var(--card-radius);padding:0.5rem;box-shadow:0 6px 20px rgba(18,18,60,0.03)}
.modern-table{width:100%;border-collapse:collapse}
.modern-table thead th{font-weight:700;font-size:.82rem;color:#495057;padding:.85rem .75rem;text-align:left;border-bottom:1px solid #eef2f7}
.modern-table tbody td{padding:.75rem .75rem;vertical-align:middle;border-bottom:1px solid #f4f6fa}
.modern-table tbody tr:hover{background:#fbfbfe}
.small-badge{padding:.35rem .6rem;border-radius:999px;font-weight:700;font-size:.78rem}
.badge-rejected{background:linear-gradient(90deg,#ff6b6b,#ff8a80);color:#fff}
.type-family{background:#eef2ff;color:#345;display:inline-block;padding:.35rem .6rem;border-radius:8px;font-weight:700}
.type-group{background:#fff0ea;color:#7a3d2f;display:inline-block;padding:.35rem .6rem;border-radius:8px;font-weight:700}
.action-btn{border:0;background:#fff;padding:.45rem .6rem;border-radius:8px;cursor:pointer}
.action-btn .fa{color:var(--muted)}

/* responsive tweaks */
@media (max-width:767px){
  .stats-grid{flex-direction:column;align-items:flex-start}
  .page-title h3{font-size:1rem}
  .stat-card{min-width:100%}
}

.empty-state{padding:3rem;text-align:center;color:var(--muted)}

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

<div class="container-fluid">
  <div class="header-row">
    <div class="page-title">
      <div class="icon bg-white rounded-3 p-2 shadow-sm"><i class="fas fa-ban text-danger"></i></div>
      <div>
        <h3>Rejected Registrations</h3>
        <div class="page-sub">Manage and review all rejected bookings</div>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <small>Total Rejected</small>
        <h4 id="totalCount">0</h4>
      </div>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()" title="Clear Filters">
            <i class="fas fa-times-circle me-1"></i> Clear Filters
        </button>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-columns me-1"></i> Columns
            </button>
            <ul class="dropdown-menu p-2 shadow-sm" aria-labelledby="columnsDropdown" id="column-toggles" style="max-height: 400px; overflow-y: auto;">
                <!-- dynamically populated -->
            </ul>
        </div>
        <button class="btn btn-primary btn-sm" onclick="exportRejectedData('excel')" title="Export to Excel">
            <i class="fas fa-file-excel me-1"></i> Excel
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="exportRejectedData('pdf')" title="Export to PDF">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </button>
      </div>

      <div class="card-compact p-2" style="background:#fff;border-radius:10px;display:flex;align-items:center;gap:.6rem">
        <i class="fa fa-info-circle text-muted"></i>
        <small class="text-muted">Tip: Use search to quickly find a booking</small>
      </div>
    </div>
  </div>

  <div class="card-compact p-3 mb-3">
    <div class="search-row mb-2">
      <div class="input-search">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
          <input id="searchInput" type="text" class="form-control border-start-0" placeholder="Search by booking ID, name, or phone...">
        </div>
      </div>

      <div style="min-width:140px;display:flex;gap:.5rem;">
        <button id="refreshBtn" class="btn btn-outline-primary btn-sm">Refresh</button>
      
      </div>
    </div>

    <div id="api-table-container">
      <div class="loading-spinner text-center py-4">
        <div class="spinner-border" role="status"></div>
        <p class="mt-2 text-muted">Loading rejected registrations...</p>
      </div>
    </div>
    <div id="pagination-container" class="p-3 border-top bg-white"></div>
  </div>
</div>

<script>
const API_URL = '/api/registration/rejected-list';
let allData = [];
let currentPage = 1;

const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:2500});

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
    { key: 'status', label: 'Status', defaultVisible: true, filterable: false },
    { key: 'actions', label: 'Action', defaultVisible: true, filterable: false }
];

function initDynamicHeaders() {
    let dropdownHtml = '';
    DB_COLUMNS.forEach((col, idx) => {
        const checked = col.defaultVisible ? 'checked' : '';
        const exportAttr = col.exportKey ? `data-export-key="${col.exportKey}"` : '';
        dropdownHtml += `<li><label class="dropdown-item"><input type="checkbox" class="col-toggle" value="${idx}" ${exportAttr} ${checked}> ${col.label}</label></li>`;
    });
    document.getElementById('column-toggles').innerHTML = dropdownHtml;
}

async function fetchData(){
  try{
    const params = new URLSearchParams();
    params.append('page', currentPage);
    params.append('per_page', 25);
    const s = document.getElementById('searchInput')?.value.trim();
    if (s) params.append('search', s);

    DB_COLUMNS.forEach(col => {
        if (col.filterable) {
            const val = document.getElementById(`filter_${col.key}`)?.value?.trim();
            if (val) {
                params.append(`filter_${col.key}`, val);
            }
        }
    });

    const res = await fetch(API_URL + '?' + params.toString());
    const json = await res.json();
    const data = json.data || [];
    allData = data;
    renderTable(data);
    updateStats(json.meta);
    if(json.meta) renderPagination(json.meta, '#pagination-container');
  }catch(e){
    Toast.fire({icon:'error',title:'Failed to load data'});
    document.getElementById('api-table-container').innerHTML = `<div class="empty-state"><h5>Error loading data</h5><p class="text-muted">Try refreshing or check your connection.</p></div>`;
  }
}

function updateStats(meta){
  document.getElementById('totalCount').textContent = meta ? meta.total : 0;
}

function renderTable(data){
  const rejected = data.filter(r=>r.status==='rejected');
  if(rejected.length===0 && data.length===0){
    document.getElementById('api-table-container').innerHTML = `
      <div class="empty-state">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <h5>No Rejected Registrations</h5>
        <p class="text-muted">There are no rejected bookings at the moment.</p>
      </div>`;
    return;
  }
  
  // Use data if filtered by server, but kept filter just in case
  const recordsToRender = data.length > 0 ? data : rejected;

    let headersHtml = '<tr>';
    DB_COLUMNS.forEach((col, idx) => {
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
        headersHtml += `<th data-col-idx="${idx}" class="text-nowrap">${col.label} ${filterHtml}</th>`;
    });
    headersHtml += '</tr>';

    let html = `
    <div class="table-responsive" style="min-height: 500px; overflow-x: auto;">
      <table class="modern-table table align-middle" id="data-table">
        <thead>${headersHtml}</thead>
        <tbody>`;

    recordsToRender.forEach((row, i) => {
        const typeHtml = (row.type==='family')? `<span class="type-family">Family</span>` : `<span class="type-group">Group</span>`;
        html += '<tr>';
        DB_COLUMNS.forEach((col, idx) => {
            let val = '';
            if (col.key === 'index') val = ((currentPage - 1) * 25) + i + 1;
            else if (col.key === 'status') val = `<span class="small-badge badge-rejected">Rejected</span>`;
            else if (col.key === 'actions') val = `
                <div class="d-flex justify-content-end align-items-center gap-1">
                    <button class="btn btn-sm btn-outline-success" onclick="changeStatus('${row.type}',${row.id},'pending')"><i class="fas fa-undo"></i> Bring Back</button>
                </div>`;
            else if (col.key === 'booking_id') val = `<strong>${row.display_id || row.id}</strong><br>`;
            else if (col.key === 'type') val = typeHtml;
            else if (col.key === 'total_persons') val = `<strong>${row.total_persons ?? '-'}</strong>`;
            else if (col.key === 'city') val = row.city_name ?? row.city ?? '';
            else if (col.key === 'state') val = row.state_name ?? row.state ?? '';
            else if (col.key === 'aanchal') val = row.aanchal_name ?? row.aanchal ?? '';
            else val = row[col.key] ?? '-';
            
            html += `<td data-col-idx="${idx}">${val}</td>`;
        });
        html += '</tr>';
    });

  html += `</tbody></table></div>`;
  document.getElementById('api-table-container').innerHTML = html;
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

function clearFilters() {
    document.querySelectorAll('.column-filter').forEach(el => el.value = '');
    document.getElementById('searchInput').value = '';
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

function applyColumnVisibility() {
    document.querySelectorAll('.col-toggle').forEach(checkbox => {
        const colIdx = checkbox.value;
        const isChecked = checkbox.checked;
        const table = document.getElementById('data-table');
        if (table) {
            table.querySelectorAll('th, td').forEach(cell => {
                if(cell.getAttribute('data-col-idx') == colIdx) cell.style.display = isChecked ? '' : 'none';
            });
        }
    });
}

function changeStatus(type,id,status){
  Swal.fire({title:'Change Status?',text:'Change status to Pending?',icon:'question',showCancelButton:true,confirmButtonText:'Yes'})
  .then(res=>{if(res.isConfirmed){
    fetch(`/api/registration/${type}/${id}/status`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({status})})
    .then(r=>r.json()).then(j=>{if(j.success){Toast.fire({icon:'success',title:'Status changed'});fetchData()}else{Toast.fire({icon:'error',title:j.message||'Failed'})}})
    .catch(()=>Toast.fire({icon:'error',title:'Network error'}));
  }});
}

document.addEventListener('DOMContentLoaded',function(){
  initDynamicHeaders();
  fetchData();
  document.getElementById('refreshBtn').addEventListener('click',fetchData);
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('keypress',function(e){
      if(e.key === 'Enter') fetchData();
  });
  
  document.querySelectorAll('.col-toggle').forEach(el => {
      el.addEventListener('change', applyColumnVisibility);
  });
  
  document.getElementById('column-toggles').addEventListener('click', function (e) {
      e.stopPropagation();
  });
});

async function exportRejectedData(format) {
    try {
        Swal.fire({title: 'Exporting Data', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
        
        const params = new URLSearchParams();
        params.append('format', format);
        
        const s = document.getElementById('searchInput')?.value.trim();
        if (s) params.append('search', s);
        
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

        const exportUrl = `/api/registration/rejected-export?${params.toString()}`;
        
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
        link.download = `rejected-registrations-${new Date().toISOString().slice(0,10)}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
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