@extends('admin.layout')

@section('title', 'Pending Registrations')

@section('content')
<style>
    .table-responsive {
        max-height: 65vh;
        min-height: 400px;
        overflow: auto;
    }
    .table-responsive thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa; /* standard table-light color */
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
        background: #f8f9fa;
    }
    .table-hover tbody tr:hover td:last-child {
        background: #f2f2f2;
    }
</style>
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
        <div>
            <h2 class="mb-0">Pending Registrations</h2>
            <small class="text-muted">Showing all pending Family &amp; Group bookings</small>
        </div>

        <!-- EXPORT BUTTONS & COLUMNS DROPDOWN -->
        <div class="col-md-6 d-flex gap-2 justify-content-md-end">
            <button class="btn btn-outline-danger shadow-sm btn-sm" id="btn-clear-filters">
                <i class="bi bi-x-circle"></i> Clear Filters
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-layout-three-columns me-1"></i> Columns
                </button>
                <ul class="dropdown-menu p-2 shadow-sm" aria-labelledby="columnsDropdown" id="column-toggles" style="max-height: 400px; overflow-y: auto;">
                    <!-- dynamically populated -->
                </ul>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" onclick="exportData('excel')" title="Export to Excel">
                    <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
            </div>
        </div>

        <!-- SEARCH CONTROLS (compact & inline) -->
        <form id="searchForm" class="d-flex gap-2 align-items-center ms-auto" onsubmit="event.preventDefault(); loadBookings();">
            <div class="input-group input-group-sm" style="width:260px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="search" class="form-control" placeholder="Search by name or phone">
                <button type="button" class="btn btn-outline-secondary" id="clear-search" title="Clear"><i class="bi bi-x-lg"></i></button>
            </div>

            <div class="input-group input-group-sm" style="width:240px;">
                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                <input type="text" id="booking_id_search" class="form-control" placeholder="Search by Booking ID">
                <button type="button" class="btn btn-outline-secondary" id="clear-booking-id" title="Clear"><i class="bi bi-x-lg"></i></button>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" id="btn-search" type="button"><i class="bi bi-search me-1"></i>Search</button>
                <button class="btn btn-outline-secondary btn-sm" id="btn-refresh" type="button" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr id="table-headers-row">
                            <!-- dynamically populated -->
                        </tr>
                    </thead>
                    <tbody id="booking-table">
                        <tr><td colspan="10" class="text-center text-muted py-4">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div id="pagination-container" class="p-3 border-top bg-white"></div>
        </div>
    </div>
</div>

<!-- View More Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">Booking Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewModalBody">
        <div class="text-center text-muted">Loading details...</div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">Edit Booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
            <input type="hidden" id="edit_type">
            <input type="hidden" id="edit_id">

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input class="form-control" id="edit_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Relationship Type</label>
                    <input class="form-control" id="edit_father_name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input class="form-control" id="edit_phone">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Aadhar</label>
                    <input class="form-control" id="edit_aadhar_number">
                </div>
                

                <div class="col-md-4">
                    <label class="form-label">MID</label>
                    <input class="form-control" id="edit_mid">
                </div>
                

                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <select id="edit_city" class="form-select">
                        <option value="">-- Select City --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <select id="edit_state" class="form-select">
                        <option value="">-- Select State --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Aanchal</label>
                    <select id="edit_aanchal" class="form-select">
                        <option value="">-- Select Aanchal --</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Travel Type</label>
                    <input class="form-control" id="edit_travel_type">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Check-in Date</label>
                    <input type="date" class="form-control" id="edit_check_in_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-in Time</label>
                    <input type="time" class="form-control" id="edit_check_in_time">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-out Date</label>
                    <input type="date" class="form-control" id="edit_check_out_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-out Time</label>
                    <input type="time" class="form-control" id="edit_check_out_time">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Family Coming</label>
                    <select id="edit_family_coming" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">No. of Children</label>
                    <input type="number" class="form-control" id="edit_no_of_children" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Total Persons</label>
                    <input type="number" class="form-control" id="edit_total_persons" min="1">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Total Male</label>
                    <input type="number" class="form-control" id="edit_total_male" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Total Female</label>
                    <input type="number" class="form-control" id="edit_total_female" min="0">
                </div>
                <div class="col-md-3">
    <label class="form-label">60+ Members</label>
    <select id="edit_sixty_plus_members" class="form-select">
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
</div>


                <div class="col-md-4">
                    <label class="form-label">60+ Male</label>
                    <input type="number" class="form-control" id="edit_sixty_plus_male" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">60+ Female</label>
                    <input type="number" class="form-control" id="edit_sixty_plus_female" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Is Veer Parivar</label>
                    <select id="edit_is_veer_parivar" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Veer Relation</label>
                    <input class="form-control" id="edit_veer_relation">
                </div>

                <div class="col-md-4">
                    <label class="form-label">MS Name</label>
                    <input class="form-control" id="edit_ms_name">
                </div>
            </div>

            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<style>
/* Ensure modals appear above fixed headers */
.modal { z-index: 25000 !important; }
.modal-backdrop { z-index: 24000 !important; }

/* Table & badge tweaks */
.table-hover tbody tr:hover { background-color: #fbfbfd; }
.badge-type { font-weight:600; text-transform:capitalize; font-size:0.83rem; padding:0.35rem 0.55rem; border-radius:0.5rem; }
.action-btns .btn { margin-left:6px; }
@media (max-width: 768px) {
    .input-group { width: 100% !important; }
    #searchForm { width: 100%; }
}
</style>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function(){
    /**************************************************************************
     * Config
     **************************************************************************/
    const externalApiBase = "https://apiv1.sadhumargi.com/api";
    const externalToken = "vPW6doIdkAdf"; // update if changed
    const localApiBase = "/api/registration";
    let allStates = [], allCities = [], allAnchals = [];

    /**************************************************************************
     * Global headers (Laravel CSRF + external bearer for external calls)
     **************************************************************************/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Authorization': `Bearer ${externalToken}`
        }
    });

    /**************************************************************************
     * Helpers (populate/select/find)
     **************************************************************************/
    function showLoader(selector){ $(selector).show(); }
    function hideLoader(selector){ $(selector).hide(); }

    function populateSelect($sel, items, valueKey, textKey, placeholder) {
        $sel.empty().append(`<option value="">${placeholder}</option>`);
        if (!Array.isArray(items)) return;
        items.forEach(it => {
            const val = it[valueKey] ?? it.id ?? it.city_id ?? it.state_id ?? it.anchal_id ?? it.name ?? '';
            const txt = it[textKey] ?? it.name ?? it.city_name ?? it.state_name ?? it;
            $sel.append(`<option value="${val}">${txt}</option>`);
        });
        $sel.trigger('change');
    }

    function setSelectValueSelector(selector, value) {
        const $sel = $(selector);
        if (!value) { $sel.val('').trigger('change'); return; }
        if ($sel.find(`option[value="${value}"]`).length) {
            $sel.val(value).trigger('change'); return;
        }
        // fallback by text match
        const opt = $sel.find('option').filter(function(){
            return $(this).text().trim().toLowerCase() === String(value).trim().toLowerCase();
        }).first();
        if (opt.length) $sel.val(opt.val()).trigger('change');
    }

    function findIn(list, val) {
        if (!val || !Array.isArray(list)) return null;
        const s = String(val).trim().toLowerCase();
        return list.find(x => {
            return ['id','state_id','city_id','anchal_id','name','city_name','state_name'].some(k => {
                if (!x[k]) return false;
                return String(x[k]).trim().toLowerCase() === s;
            });
        }) || list.find(x => (x.name && String(x.name).trim().toLowerCase() === s));
    }

    /**************************************************************************
     * Load reference data from mrm.sadhumargi.org/api/cities
     * Single call returns cities with embedded state & anchal info
     **************************************************************************/
    function loadReferenceData() {
        return $.get('https://mrm.sadhumargi.org/api/cities')
            .done(function(response){
                allCities = Array.isArray(response.cities) ? response.cities : [];

                // Derive unique states
                const stateMap = {};
                allCities.forEach(c => {
                    if (c.state_id && !stateMap[c.state_id])
                        stateMap[c.state_id] = { state_id: c.state_id, state_name: c.state_name };
                });
                allStates = Object.values(stateMap);

                // Derive unique anchals
                const anchalMap = {};
                allCities.forEach(c => {
                    if (c.anchal_id && !anchalMap[c.anchal_id])
                        anchalMap[c.anchal_id] = { anchal_id: c.anchal_id, name: c.anchal_name };
                });
                allAnchals = Object.values(anchalMap);

                // Populate city selects
                populateSelect($("select[name='city']"), allCities, 'city_id', 'city_name', 'Select City');
                if ($('#edit_city').length) populateSelect($('#edit_city'), allCities, 'city_id', 'city_name', '-- Select City --');

                // Populate state selects
                populateSelect($("select[name='state']"), allStates, 'state_id', 'state_name', 'Select State');
                if ($('#edit_state').length) populateSelect($('#edit_state'), allStates, 'state_id', 'state_name', '-- Select State --');

                // Populate anchal selects
                populateSelect($("select[name='aanchal']"), allAnchals, 'anchal_id', 'name', 'Select Aanchal');
                if ($('#edit_aanchal').length) populateSelect($('#edit_aanchal'), allAnchals, 'anchal_id', 'name', '-- Select Aanchal --');

                // Init select2
                try { $("select[name='city']").select2({ theme:'bootstrap4', placeholder:"Select City", allowClear:true, width:'100%' }); } catch(e){}
                try { $("#edit_city").select2({ theme:'bootstrap4', placeholder:"-- Select City --", allowClear:true, width:'100%' }); } catch(e){}
                try { $("select[name='state']").select2({ theme:'bootstrap4', placeholder:"Select State", allowClear:true, width:'100%' }); } catch(e){}
                try { $("#edit_state").select2({ theme:'bootstrap4', placeholder:"-- Select State --", allowClear:true, width:'100%' }); } catch(e){}
                try { $("select[name='aanchal']").select2({ theme:'bootstrap4', placeholder:"Select Aanchal", allowClear:true, width:'100%' }); } catch(e){}
                try { $("#edit_aanchal").select2({ theme:'bootstrap4', placeholder:"-- Select Aanchal --", allowClear:true, width:'100%' }); } catch(e){}
            })
            .fail(function(){ console.warn('loadReferenceData failed'); })
            .then(function(){ return true; });
    }

    /**************************************************************************
     * City change event -> set state & anchal from local allCities data
     **************************************************************************/
    $("select[name='city']").on('change', function(){
        const cityId = $(this).val();
        if (!cityId) {
            $("select[name='state']").val('').trigger('change');
            $("select[name='aanchal']").val('').trigger('change');
            $("#edit_state").val('').trigger('change');
            $("#edit_aanchal").val('').trigger('change');
            return;
        }
        const cityObj = allCities.find(c => String(c.city_id) === String(cityId));
        if (cityObj) {
            setSelectValueSelector("select[name='state']", cityObj.state_id);
            setSelectValueSelector("select[name='aanchal']", cityObj.anchal_id);
            setSelectValueSelector("#edit_state", cityObj.state_id);
            setSelectValueSelector("#edit_aanchal", cityObj.anchal_id);
        }
    });

    /**************************************************************************
     * fetch-profiles by phone -> autocomplete + set city
     **************************************************************************/
    $("input[name='phone']").on('blur', function(){
        const phone = $(this).val().trim();
        if (phone.length !== 10) return;
        $("#nameLoader").show();
        $.ajax({
            url: `${externalApiBase}/fetch-profiles`,
            method: 'POST',
            data: { mobile_number: phone }
        }).done(function(response){
            const profiles = response.profiles || response.data || [];
            if (!Array.isArray(profiles) || profiles.length === 0) {
                Swal.fire("Info", "कोई प्रोफ़ाइल नहीं मिली!", "info");
                return;
            }
            const suggestions = profiles.map(p => ({
                label: `${p.first_name ?? ''} ${p.last_name ?? ''}`.trim(),
                value: `${p.first_name ?? ''} ${p.last_name ?? ''}`.trim(),
                member_id: p.member_id
            }));
            const nameInput = $("input[name='name']");
            try {
                nameInput.autocomplete({ source: suggestions, minLength: 0, select: function(e, ui){ $("#member_id_field").val(ui.item.member_id); } }).focus().autocomplete("search", "");
            } catch(e){}
            const first = profiles[0];
            if (first.city) {
                $("select[name='city'] option").filter(function(){
                    return $(this).text().trim().toLowerCase() === String(first.city).trim().toLowerCase();
                }).prop("selected", true).trigger('change');
            }
        }).fail(function(){
            Swal.fire("Error", "MID fetch failed for this mobile number.", "error");
        }).always(function(){ $("#nameLoader").hide(); });
    });

    // reset dependent fields when phone input changed
    $("input[name='phone']").on('input', function(){
        $("input[name='name']").val('');
        $("#member_id_field").val('');
        $("select[name='city']").val('').trigger('change');
        $("select[name='state']").val('').trigger('change');
        $("select[name='aanchal']").val('').trigger('change');
        $("#edit_city").val('').trigger('change');
        $("#edit_state").val('').trigger('change');
        $("#edit_aanchal").val('').trigger('change');
    });

    /**************************************************************************
     * Bookings list / view / edit / reject
     **************************************************************************/
    const $tbody = $('#booking-table');
    const $search = $('#search');
    const $bookingIdSearch = $('#booking_id_search');
    const $btnSearch = $('#btn-search');
    let currentPage = 1;

    // helpers for UI controls
    $('#btn-clear-filters').on('click', function() {
        $('.column-filter').val('');
        $search.val('');
        $bookingIdSearch.val('');
        currentPage = 1;
        loadBookings();
    });

    $('#btn-refresh').on('click', function(){
        $search.val(''); $bookingIdSearch.val('');
        // trigger background fetch for reference data
        loadReferenceData();
        loadBookings();
    });

    function buildQueryParams() {
        const params = {
            page: currentPage,
            per_page: 25
        };
        const s = $search.val()?.trim();
        const b = $bookingIdSearch.val()?.trim();
        if (s) params.search = s;
        if (b) params.booking_id = b;

        DB_COLUMNS.forEach(col => {
            if (col.filterable) {
                const val = $(`#filter_${col.key}`).val()?.trim();
                if (val) {
                    params[`filter_${col.key}`] = val;
                }
            }
        });

        return params;
    }

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
        { key: 'city_state', label: 'City & State', defaultVisible: true, filterable: false },
        { key: 'aanchal', exportKey: 'aanchal', label: 'Aanchal', defaultVisible: false, filterable: true, type: 'text' },
        { key: 'travel_type', exportKey: 'travel_type', label: 'Travel Type', defaultVisible: false, filterable: true, type: 'text' },
        { key: 'check_in_out', label: 'Check In/Out', defaultVisible: true, filterable: false },
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
        { key: 'actions', label: 'Actions', defaultVisible: true, filterable: false }
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
                        <i class="bi bi-funnel"></i>
                    </button>
                    <div class="dropdown-menu p-2 shadow" style="min-width: 200px;">
                        ${inputElement}
                    </div>
                </div>`;
            }

            const style = col.defaultVisible ? '' : 'display: none;';
            headersHtml += `<th style="${style}" data-col-idx="${idx}" class="text-nowrap">${col.label} ${filterHtml}</th>`;
        });

        $('#column-toggles').html(dropdownHtml);
        $('#table-headers-row').html(headersHtml);

        $('.column-filter').on('change', function() {
            currentPage = 1;
            loadBookings();
        });
        $('.column-filter[type="text"], .column-filter[type="number"]').on('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                currentPage = 1;
                loadBookings();
            }
        });
        
        $('.col-toggle').on('change', function() {
            applyColumnVisibility();
        });
    }

    async function loadBookings() {
        $tbody.html(`<tr><td colspan="${DB_COLUMNS.length}" class="text-center text-muted py-4">Loading...</td></tr>`);
        try {
            // No longer awaiting loadReferenceData to ensure fast rendering.
            // City/State names are already provided by the backend endpoint.
            
            const qs = new URLSearchParams(buildQueryParams()).toString();
            const res = await axios.get(`${localApiBase}/bookings?${qs}`);
            const data = res.data.data || [];
            if (!data.length) {
                $tbody.html(`<tr><td colspan="${DB_COLUMNS.length}" class="text-center text-muted py-4">No pending bookings found</td></tr>`);
                if(res.data.meta) renderPagination(res.data.meta, '#pagination-container');
                return;
            }

            const rows = data.map((b, i) => {
                const getCity = v => findIn(allCities, v)?.city_name ?? findIn(allCities, v)?.name ?? (typeof v === 'object' ? v?.city_name : v) ?? '';
                const getState = v => findIn(allStates, v)?.state_name ?? findIn(allStates, v)?.name ?? (typeof v === 'object' ? v?.state_name : v) ?? '';
                const getAanchal = v => findIn(allAnchals, v)?.name ?? (typeof v === 'object' ? v?.name : v) ?? '';
                
                const typeBadge = b.type === 'family' ? 'info' : 'warning';
                
                const actionHtml = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-primary" title="View" onclick="viewMore('${b.type}', ${b.id})"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-outline-secondary" title="Edit" onclick="openEdit('${b.type}', ${b.id})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-outline-danger" title="Reject" onclick="rejectBooking('${b.type}', ${b.id})"><i class="bi bi-x-lg"></i></button>
                    </div>

                    ${ (b.status && b.status === 'completed') ? `
                        <div class="mt-2">
                            <button class="btn btn-primary btn-sm show-booking-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bookingModal"
                                    data-hotel="${b.hotel?.hotel_name ?? 'N/A'}"
                                    data-rooms="${(b.booked_rooms || []).map(r=>r.room_number).join(', ')}">
                                🏨 Show Booking
                            </button>
                            <form action="/group-booking/checkout/${b.id}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to check out this booking?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-warning btn-sm">🔓 Check Out</button>
                            </form>
                        </div>
                    ` : `
                        <div class="mt-2">
                            ${(b.check_out_date && b.check_out_date < new Date().toISOString().split('T')[0]) ? `
                                <button type="button" class="btn btn-secondary btn-sm" onclick="Swal.fire('Not Allowed', 'This is a past booking. Please extend check-out date to allot room.', 'error')" title="Cannot Allot Room">🚫 Past Booking</button>
                            ` : `
                            <form action="/alot-room" method="GET" class="d-inline">
                                <input type="hidden" name="booking_id" value="${b.id}">
                                <input type="hidden" name="total_persons" value="${b.total_persons ?? ''}">
                                <input type="hidden" name="booking_type" value="${b.type}">
                                <button type="submit" class="btn btn-success btn-sm" title="Allot Room">✅ Allot Room</button>
                            </form>
                            `}
                        </div>
                    `}
                `;

                let trHtml = '<tr>';
                DB_COLUMNS.forEach((col, idx) => {
                    const style = col.defaultVisible ? '' : 'display: none;';
                    let val = '';
                    if (col.key === 'index') val = ((currentPage - 1) * 25) + i + 1;
                    else if (col.key === 'actions') val = actionHtml;
                    else if (col.key === 'type') val = `<span class="badge bg-${typeBadge} text-dark badge-type">${b.type}</span>`;
                    else if (col.key === 'booking_id') val = b.display_id ?? b.booking_id ?? (b.type === 'family' ? 'F-' : 'G-') + (b.id + 100);
                    else if (col.key === 'city_state') {
                        const c = getCity(b.city);
                        const s = getState(b.state);
                        val = (c && s) ? `${c}, ${s}` : (c || s || '-');
                    }
                    else if (col.key === 'check_in_out') {
                        const ciDate = b.check_in_date ?? '';
                        const ciTime = b.check_in_time ?? '';
                        const coDate = b.check_out_date ?? '';
                        const coTime = b.check_out_time ?? '';
                        const ci = ciDate ? `${ciDate} ${ciTime}`.trim() : '-';
                        const co = coDate ? `${coDate} ${coTime}`.trim() : '-';
                        val = `<div><small><strong>In:</strong> ${ci}</small><br><small><strong>Out:</strong> ${co}</small></div>`;
                    }
                    else if (col.key === 'aanchal') val = getAanchal(b.aanchal);
                    else val = b[col.key] ?? '';
                    
                    trHtml += `<td style="${style}" data-col-idx="${idx}">${val}</td>`;
                });
                trHtml += '</tr>';
                return trHtml;
            }).join('');
            
            $tbody.html(rows);
            if(res.data.meta) {
                renderPagination(res.data.meta, '#pagination-container');
            }
            applyColumnVisibility();
        } catch (err) {
            console.error(err);
            $tbody.html(`<tr><td colspan="10" class="text-danger text-center py-4">Error loading data</td></tr>`);
        }
    }

    function renderPagination(meta, containerId) {
        if (!meta || meta.total === 0) {
            $(containerId).html('');
            return;
        }
        let html = `
        <div class="d-flex justify-content-between align-items-center">
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
        $(containerId).html(html);
    }
    
    window.changePage = function(page) {
        currentPage = page;
        loadBookings();
    };

    // wire search
    $btnSearch.on('click', () => loadBookings());
    $search.on('keypress', function(e){ if (e.key === 'Enter') { e.preventDefault(); loadBookings(); }});
    $bookingIdSearch.on('keypress', function(e){ if (e.key === 'Enter') { e.preventDefault(); loadBookings(); }});

    function applyColumnVisibility() {
        $('.col-toggle').each(function() {
            const colIdx = $(this).val();
            const isChecked = $(this).is(':checked');
            // Hide/show headers
            $('table thead tr').each(function() {
                $(this).find('th').eq(colIdx).toggle(isChecked);
            });
            // Hide/show cells
            $('table tbody tr').each(function() {
                $(this).find('td').eq(colIdx).toggle(isChecked);
            });
        });
    }

    /**************************************************************************
     * View More
     **************************************************************************/
    window.viewMore = async function(type, id) {
        const modalBody = $('#viewModalBody');
        const modal = new bootstrap.Modal($('#viewModal')[0]);
        modalBody.html(`<div class="text-center text-muted">Loading details...</div>`);
        modal.show();
        try {
            const res = await axios.get(`${localApiBase}/${type}/${id}`);
            const booking = res.data.booking;
            const getCity = v => findIn(allCities, v)?.city_name ?? findIn(allCities, v)?.name ?? v ?? '';
            const getState = v => findIn(allStates, v)?.state_name ?? findIn(allStates, v)?.name ?? v ?? '';
            const getAanchal = v => findIn(allAnchals, v)?.name ?? v ?? '';
            const members = booking.family_members || booking.group_members || [];
            let html = `<div class="table-responsive"><table class="table table-bordered">`;
            html += `<tr><th>Booking ID</th><td>${booking.booking_id ?? '—'}</td></tr>`;
            html += `<tr><th>Name</th><td>${booking.name ?? ''}</td></tr>`;
            html += `<tr><th>${booking.relationship_type ?? 'Relation'}</th><td>${booking.father_name ?? '—'}</td></tr>`;
            html += `<tr><th>Phone</th><td>${booking.phone ?? ''}</td></tr>`;
            html += `<tr><th>Aadhar</th><td>${booking.aadhar_number ?? ''}</td></tr>`;
            html += `<tr><th>City</th><td>${getCity(booking.city)}</td></tr>`;
            html += `<tr><th>State</th><td>${getState(booking.state)}</td></tr>`;
            html += `<tr><th>Aanchal</th><td>${getAanchal(booking.aanchal)}</td></tr>`;
            html += `<tr><th>Check-in</th><td>${booking.check_in_date ?? ''} ${booking.check_in_time ?? ''}</td></tr>`;
            html += `<tr><th>Check-out</th><td>${booking.check_out_date ?? ''} ${booking.check_out_time ?? ''}</td></tr>`;
            html += `<tr><th>Total Persons</th><td>${booking.total_persons ?? booking.total_members ?? ''}</td></tr>`;
            html += `</table></div>`;
            if (members.length) {
                html += `<h6 class="mt-3">Members:</h6><ul>${members.map(m=>`<li>${m.name} (${m.aadhar_number ?? ''})</li>`).join('')}</ul>`;
            } else {
                html += `<p class="text-muted">No members found</p>`;
            }
            modalBody.html(html);
        } catch (err) {
            console.error(err);
            modalBody.html(`<p class="text-danger">Error loading details.</p>`);
        }
    };

    /**************************************************************************
     * Open Edit
     **************************************************************************/
    window.openEdit = async function(type, id) {
        const modalEl = $('#editModal')[0];
        const modal = new bootstrap.Modal(modalEl);
        $('#editForm')[0].reset();
        $('#edit_type').val(type);
        $('#edit_id').val(id);

        try {
            const res = await axios.get(`${localApiBase}/${type}/${id}`);
            const booking = res.data.booking;

            $('#edit_name').val(booking.name ?? '');
            $('#edit_father_name').val(booking.father_name ?? '');
            $('#edit_phone').val(booking.phone ?? '');
            $('#edit_aadhar_number').val(booking.aadhar_number ?? '');
            $('#edit_age').val(booking.age ?? '');
            $('#edit_mid').val(booking.mid ?? '');
            $('#edit_ms_name').val(booking.ms_name ?? '');

            setSelectValueSelector('#edit_city', booking.city);
            setSelectValueSelector('#edit_state', booking.state);
            setSelectValueSelector('#edit_aanchal', booking.aanchal);

            $('#edit_travel_type').val(booking.travel_type ?? '');
            $('#edit_check_in_date').val(booking.check_in_date ?? '');
            $('#edit_check_in_time').val(booking.check_in_time ?? '');
            $('#edit_check_out_date').val(booking.check_out_date ?? '');
            $('#edit_check_out_time').val(booking.check_out_time ?? '');
            $('#edit_family_coming').val(booking.family_coming ?? (booking.total_members ? '1' : '0'));
            $('#edit_no_of_children').val(booking.no_of_children ?? booking.child_count ?? '');
            // total_persons editable directly
            $('#edit_total_persons').val(booking.total_persons ?? (parseInt(booking.total_members) ? (parseInt(booking.total_members)+1) : ''));

            $('#edit_total_male').val(booking.total_male ?? '');
            $('#edit_total_female').val(booking.total_female ?? '');
            $('#edit_sixty_plus_members').val(booking.sixty_plus_members ?? '');
            $('#edit_sixty_plus_male').val(booking.sixty_plus_male ?? '');
            $('#edit_sixty_plus_female').val(booking.sixty_plus_female ?? '');
            $('#edit_is_veer_parivar').val(booking.is_veer_parivar ?? 0);
            $('#edit_veer_relation').val(booking.veer_relation ?? '');

            modal.show();
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Could not load booking for edit.', 'error');
        }
    };

    /**************************************************************************
     * Submit edit form
     **************************************************************************/
    $('#editForm').on('submit', async function(e){
        e.preventDefault();
        const type = $('#edit_type').val();
        const id = $('#edit_id').val();
        const payload = {
            name: $('#edit_name').val(),
            father_name: $('#edit_father_name').val(),
            phone: $('#edit_phone').val(),
            aadhar_number: $('#edit_aadhar_number').val(),
            age: $('#edit_age').val(),
            mid: $('#edit_mid').val(),
            ms_name: $('#edit_ms_name').val(),
            city: $('#edit_city').val(),
            state: $('#edit_state').val(),
            aanchal: $('#edit_aanchal').val(),
            travel_type: $('#edit_travel_type').val(),
            check_in_date: $('#edit_check_in_date').val(),
            check_in_time: $('#edit_check_in_time').val(),
            check_out_date: $('#edit_check_out_date').val(),
            check_out_time: $('#edit_check_out_time').val(),
            family_coming: $('#edit_family_coming').val(),
            no_of_children: $('#edit_no_of_children').val(),
            total_persons: $('#edit_total_persons').val(),
            total_male: $('#edit_total_male').val(),
            total_female: $('#edit_total_female').val(),
            sixty_plus_members: $('#edit_sixty_plus_members').val(),
            sixty_plus_male: $('#edit_sixty_plus_male').val(),
            sixty_plus_female: $('#edit_sixty_plus_female').val(),
            is_veer_parivar: $('#edit_is_veer_parivar').val(),
            veer_relation: $('#edit_veer_relation').val()
        };

        try {
            await axios.put(`${localApiBase}/${type}/${id}`, payload);
            Swal.fire('Saved', 'Booking updated successfully.', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            modal.hide();
            loadBookings();
        } catch (err) {
            console.error(err);
            const message = err.response?.data?.errors ? Object.values(err.response.data.errors).flat().join('\n') : (err.response?.data?.message ?? 'Could not update booking');
            Swal.fire('Error', message, 'error');
        }
    });

    /**************************************************************************
     * Reject booking
     **************************************************************************/
    window.rejectBooking = async function(type, id) {
        const confirm = await Swal.fire({
            title: 'Reject this booking?',
            text: 'This will mark the booking as rejected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject it!'
        });
        if (!confirm.isConfirmed) return;
        try {
            await axios.post(`${localApiBase}/${type}/${id}/status`, { status: 'rejected' });
            Swal.fire('Rejected', 'Booking has been rejected.', 'success');
            loadBookings();
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Could not reject booking.', 'error');
        }
    };

    /**************************************************************************
     * Optional: if edit_total_members exists, auto-calc total_persons = members + 1
     * (keeps backward compatibility if you still have total_members field somewhere)
     **************************************************************************/
    $(document).on('input', '#edit_total_members', function(){
        const totalMembers = parseInt($(this).val()) || 0;
        $('#edit_total_persons').val(totalMembers + 1);
    });

    /**************************************************************************
     * Export functionality
     **************************************************************************/
    window.exportData = async function(format) {
        try {
            // Get current search parameters
            const search = document.getElementById('search').value;
            const bookingId = document.getElementById('booking_id_search').value;
            
            // Build query parameters
            const params = new URLSearchParams();
            params.append('format', format);
            
            if (search.trim()) {
                params.append('search', search.trim());
            }
            
            if (bookingId.trim()) {
                params.append('booking_id', bookingId.trim());
            }
            
            $('.column-filter').each(function() {
                if ($(this).val().trim()) {
                    params.append($(this).attr('id'), $(this).val().trim());
                }
            });

            $('.col-toggle:checked').each(function() {
                const key = $(this).data('export-key');
                if (key) {
                    params.append('visible_columns[]', key);
                }
            });
            
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
            const exportUrl = `${localApiBase}/export?${params.toString()}`;
            
            const response = await fetch(exportUrl, {
                method: 'GET',
                headers: {
                    'Accept': format === 'excel' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/pdf',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get the blob from response
            const blob = await response.blob();
            
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `registrations-${new Date().toISOString().slice(0,10)}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
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
    };

    /**************************************************************************
     * Initial load: load reference data then bookings
     **************************************************************************/
    initDynamicHeaders();
    
    // Load reference data in the background (for edit modals and form inputs)
    loadReferenceData();
    // Load bookings immediately to prevent UI blocking
    loadBookings();
});
</script>


@endsection
