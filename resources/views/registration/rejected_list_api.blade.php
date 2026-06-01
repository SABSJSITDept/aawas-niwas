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

      <!-- Export Buttons -->
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-success btn-sm" onclick="exportRejectedData('excel')" title="Export to Excel">
          <i class="fas fa-file-excel me-1"></i>Excel
        </button>
        <button type="button" class="btn btn-danger btn-sm" onclick="exportRejectedData('pdf')" title="Export to PDF">
          <i class="fas fa-file-pdf me-1"></i>PDF
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
  </div>
</div>

<script>
const API_URL = '/api/registration/rejected-list';
let allData = [];

const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:2500});

async function fetchData(){
  try{
    const res = await fetch(API_URL);
    const json = await res.json();
    const data = json.data || [];
    allData = data;
    renderTable(data);
    updateStats(data);
  }catch(e){
    Toast.fire({icon:'error',title:'Failed to load data'});
    document.getElementById('api-table-container').innerHTML = `<div class="empty-state"><h5>Error loading data</h5><p class="text-muted">Try refreshing or check your connection.</p></div>`;
  }
}

function updateStats(data){
  const rejected = data.filter(r=>r.status==='rejected');
  document.getElementById('totalCount').textContent = rejected.length;
}

function renderTable(data){
  const rejected = data.filter(r=>r.status==='rejected');
  if(rejected.length===0){
    document.getElementById('api-table-container').innerHTML = `
      <div class="empty-state">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <h5>No Rejected Registrations</h5>
        <p class="text-muted">There are no rejected bookings at the moment.</p>
      </div>`;
    return;
  }

  let html = `
    <div class="table-responsive">
      <table class="modern-table table align-middle">
        <thead>
          <tr>
            <th style="width:110px">Booking</th>
            <th>Name</th>
            <th style="width:120px">Phone</th>
            <th style="width:85px">Persons</th>
            <th style="width:110px">Check-In</th>
            <th style="width:110px">Check-Out</th>
            <th style="width:95px">Type</th>
            <th style="width:110px">Status</th>
            <th style="width:130px;text-align:right">Action</th>
          </tr>
        </thead>
        <tbody>`;

  rejected.forEach(row=>{
    const typeHtml = (row.type==='family')? `<span class="type-family">Family</span>` : `<span class="type-group">Group</span>`;
    html += `
      <tr>
        <td><strong>${row.display_id || row.id}</strong><br></td>
        <td>${row.name || '-'}<br><small class="text-muted">${row.email || ''}</small></td>
        <td>${row.phone || '-'}</td>
        <td><strong>${row.total_persons ?? '-'}</strong></td>
        <td>${row.check_in_date || '-'}</td>
        <td>${row.check_out_date || '-'}</td>
        <td>${typeHtml}</td>
        <td><span class="small-badge badge-rejected">Rejected</span></td>
        <td style="text-align:right">
          <div class="d-flex justify-content-end align-items-center gap-1">
            <button class="btn btn-sm btn-outline-success" onclick="changeStatus('${row.type}',${row.id},'pending')"><i class="fas fa-undo"></i> Bring Back</button>
           
          </div>
        </td>
      </tr>`;
  });

  html += `</tbody></table></div>`;
  document.getElementById('api-table-container').innerHTML = html;
}

function viewDetails(id){
  // Open a modal or navigate — implement as needed
  Swal.fire({title:'Booking Details',text:'Open booking details for ID: '+id,icon:'info'});
}

function deleteBooking(type,id){
  Swal.fire({title:'Delete booking?',text:'This action is irreversible!',icon:'warning',showCancelButton:true,confirmButtonText:'Delete',confirmButtonColor:'#d33'})
  .then(res=>{if(res.isConfirmed){
    fetch(`/api/registration/${type}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
    .then(r=>r.json()).then(j=>{if(j.success){Toast.fire({icon:'success',title:'Deleted'});fetchData()}else{Toast.fire({icon:'error',title:'Delete failed'})}})
    .catch(()=>Toast.fire({icon:'error',title:'Network error'}));
  }});
}

function changeStatus(type,id,status){
  Swal.fire({title:'Change Status?',text:'Change status to Pending?',icon:'question',showCancelButton:true,confirmButtonText:'Yes'})
  .then(res=>{if(res.isConfirmed){
    fetch(`/api/registration/${type}/${id}/status`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({status})})
    .then(r=>r.json()).then(j=>{if(j.success){Toast.fire({icon:'success',title:'Status changed'});fetchData()}else{Toast.fire({icon:'error',title:j.message||'Failed'})}})
    .catch(()=>Toast.fire({icon:'error',title:'Network error'}));
  }});
}

// search & refresh
document.addEventListener('DOMContentLoaded',function(){
  fetchData();
  document.getElementById('refreshBtn').addEventListener('click',fetchData);
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('input',function(e){
    const q = e.target.value.trim().toLowerCase();
    if(!q){renderTable(allData);return}
    const filtered = allData.filter(r=>{
      const id = (r.display_id||r.id||'').toString().toLowerCase();
      const name = (r.name||'').toLowerCase();
      const phone = (r.phone||'').toLowerCase();
      return id.includes(q)||name.includes(q)||phone.includes(q);
    });
    renderTable(filtered);
  });

  
});

// Export functionality for rejected registrations
async function exportRejectedData(format) {
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
        const exportUrl = `/api/registration/rejected-export?format=${format}`;
        
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