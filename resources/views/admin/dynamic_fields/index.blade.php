@extends('admin.layout')

@section('title', 'Form Builder (Dynamic Fields)')

@section('content')
<div class="max-w-7xl mx-auto">
  
  {{-- Header --}}
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-indigo-200">
        <i class="bi bi-ui-checks"></i>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-slate-800 m-0 tracking-tight">Form Builder</h2>
        <p class="text-slate-500 text-sm m-0 mt-1 font-medium">Add custom fields to public booking forms</p>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
      <i class="bi bi-check-circle-fill text-emerald-500 text-lg mt-0.5"></i>
      <div>
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      <button type="button" class="ms-auto text-emerald-500 hover:text-emerald-700" data-bs-dismiss="alert" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Add New Field Form --}}
    <div class="lg:col-span-1">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
          <h3 class="text-lg font-bold text-slate-800 m-0"><i class="bi bi-plus-circle-fill text-indigo-500 me-2"></i>Add New Field</h3>
        </div>
        <div class="p-6">
          <form action="{{ route('admin.dynamic-fields.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Select Form <span class="text-rose-500">*</span></label>
              <select name="form_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700 font-medium bg-white" required>
                <option value="">-- Select Form --</option>
                <option value="vip">VIP / Other Booking Form</option>
                <option value="family">Family Booking Form</option>
                <option value="group">Group Booking Form</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Field Label (Title) <span class="text-rose-500">*</span></label>
              <input type="text" name="label" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700" placeholder="e.g. T-Shirt Size" required>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Field Type <span class="text-rose-500">*</span></label>
                <select name="type" id="fieldType" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700 font-medium bg-white" required onchange="toggleOptions()">
                  <option value="text">Text Input (Short)</option>
                  <option value="number">Number Input</option>
                  <option value="email">Email Input</option>
                  <option value="date">Date Input</option>
                  <option value="select">Dropdown (Select)</option>
                  <option value="radio">Radio Buttons (Single Choice)</option>
                  <option value="checkbox">Checkboxes (Multiple Choice)</option>
                </select>
            </div>

            <div class="mb-4 hidden" id="optionsDiv">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Options (Comma separated) <span class="text-rose-500">*</span></label>
              <input type="text" name="options" id="optionsInput" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700" placeholder="Small, Medium, Large">
              <p class="text-xs text-slate-500 mt-1">Example: Option 1, Option 2, Option 3</p>
            </div>

            <div class="mb-6">
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                <input type="checkbox" name="is_required" value="1" class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-semibold text-slate-700">Make this field Required</span>
              </label>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Position <span class="text-slate-400 text-xs font-normal">(Optional)</span></label>
              <select name="position" id="fieldPosition" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700 font-medium bg-white">
                <option value="end">At the end (Default)</option>
              </select>
              <p class="text-xs text-slate-500 mt-1">Select where this field should appear</p>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl font-bold transition-all shadow-md shadow-indigo-200 flex items-center justify-center gap-2 border-0">
              <i class="bi bi-save2-fill"></i> Save Custom Field
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- List of Fields --}}
    <div class="lg:col-span-2">
      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
          <h3 class="text-lg font-bold text-slate-800 m-0">Existing Custom Fields</h3>
        </div>

        <div class="flex border-b border-slate-200 bg-white px-4 pt-2">
          <button type="button" class="tab-btn px-4 py-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600" onclick="switchTab('family', this)">
            Family Booking Fields
          </button>
          <button type="button" class="tab-btn px-4 py-3 text-sm font-bold text-slate-500 border-b-2 border-transparent hover:text-slate-700" onclick="switchTab('group', this)">
            Group Booking Fields
          </button>
          <button type="button" class="tab-btn px-4 py-3 text-sm font-bold text-slate-500 border-b-2 border-transparent hover:text-slate-700" onclick="switchTab('vip', this)">
            VIP / Other Fields
          </button>
        </div>

        <div class="p-0">
          @if($fields->isEmpty())
            <div class="p-12 text-center flex flex-col items-center justify-center">
              <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-3xl mb-4">
                <i class="bi bi-ui-radios"></i>
              </div>
              <h3 class="text-lg font-bold text-slate-700 mb-1">No custom fields found</h3>
              <p class="text-slate-500 text-sm">Add your first custom field from the left panel.</p>
            </div>
          @else
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                    <th class="p-4">Form</th>
                    <th class="p-4">Label (Name)</th>
                    <th class="p-4">Type</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="fieldsTableBody">
                  @foreach($fields as $field)
                  <tr class="hover:bg-slate-50 transition-colors field-row" data-form-type="{{ $field->form_type }}">
                    <td class="p-4">
                      @if($field->form_type == 'vip')
                        <span class="inline-flex px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-xs font-bold border border-purple-100">VIP/Other</span>
                      @elseif($field->form_type == 'family')
                        <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">Family</span>
                      @elseif($field->form_type == 'group')
                        <span class="inline-flex px-2 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-100">Group</span>
                      @endif
                    </td>
                    <td class="p-4">
                      <div class="font-bold text-slate-800">{{ $field->label }}</div>
                      <div class="text-xs text-slate-500 font-mono mt-0.5">name: {{ $field->name }}</div>
                      @if($field->is_required)
                        <span class="text-[10px] text-rose-500 font-bold uppercase mt-1 inline-block">* Required</span>
                      @endif
                    </td>
                    <td class="p-4">
                      <div class="flex items-center gap-2">
                        @if($field->type == 'text')
                          <i class="bi bi-fonts text-slate-400"></i> Text
                        @elseif($field->type == 'number')
                          <i class="bi bi-123 text-slate-400"></i> Number
                        @elseif($field->type == 'email')
                          <i class="bi bi-envelope text-slate-400"></i> Email
                        @elseif($field->type == 'date')
                          <i class="bi bi-calendar-date text-slate-400"></i> Date
                        @elseif($field->type == 'select')
                          <i class="bi bi-menu-button-wide text-slate-400"></i> Select
                        @elseif($field->type == 'radio')
                          <i class="bi bi-ui-radios text-slate-400"></i> Radio
                        @elseif($field->type == 'checkbox')
                          <i class="bi bi-check-square text-slate-400"></i> Checkbox
                        @endif
                      </div>
                      @if(in_array($field->type, ['select', 'radio']) && $field->options)
                        <div class="mt-1 flex flex-wrap gap-1">
                          @foreach($field->options as $opt)
                            <span class="inline-flex px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px]">{{ $opt }}</span>
                          @endforeach
                        </div>
                      @endif
                    </td>
                    <td class="p-4 text-center">
                      @if($field->status)
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">
                          <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                        </span>
                      @endif
                    </td>
                    <td class="p-4">
                      <div class="flex items-center justify-center gap-2">
                        @if(isset($field->is_static) && $field->is_static)
                          <span class="text-xs text-slate-400 font-medium italic">Core Field</span>
                        @else
                          {{-- Toggle Status --}}
                          <form action="{{ route('admin.dynamic-fields.toggle', $field->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center border-0 transition-all shadow-sm {{ $field->status ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}" title="{{ $field->status ? 'Deactivate' : 'Activate' }}">
                              <i class="bi {{ $field->status ? 'bi-pause-fill' : 'bi-play-fill' }}"></i>
                            </button>
                          </form>

                          {{-- Delete --}}
                          <form action="{{ route('admin.dynamic-fields.destroy', $field->id) }}" method="POST" class="m-0 delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-100 transition-all border-0 shadow-sm action-delete" title="Delete">
                              <i class="bi bi-trash-fill"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(type, element) {
    // Update button styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-blue-600');
        btn.classList.add('text-slate-500', 'border-transparent');
    });
    element.classList.remove('text-slate-500', 'border-transparent');
    element.classList.add('text-blue-600', 'border-blue-600');

    // Update table rows
    let visibleCount = 0;
    document.querySelectorAll('.field-row').forEach(row => {
        if (row.getAttribute('data-form-type') === type) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

// Initial initialization
document.addEventListener('DOMContentLoaded', () => {
    switchTab('family', document.querySelector('.tab-btn'));
});
</script>

<script>
  const existingFields = @json($fields);

  document.querySelector('select[name="form_type"]').addEventListener('change', function() {
    const formType = this.value;
    const positionSelect = document.getElementById('fieldPosition');
    
    // Clear existing
    positionSelect.innerHTML = '<option value="end">At the end (Default)</option>';
    
    if (formType) {
      const relevantFields = existingFields.filter(f => f.form_type === formType);
      relevantFields.forEach(f => {
        positionSelect.innerHTML += `<option value="before_${f.id}">Before: ${f.label}</option>`;
        positionSelect.innerHTML += `<option value="after_${f.id}">After: ${f.label}</option>`;
      });
    }
  });

  function toggleOptions() {
    const type = document.getElementById('fieldType').value;
    const optionsDiv = document.getElementById('optionsDiv');
    const optionsInput = document.getElementById('optionsInput');
    
    if (type === 'select' || type === 'radio') {
      optionsDiv.classList.remove('hidden');
      optionsInput.setAttribute('required', 'required');
    } else {
      optionsDiv.classList.add('hidden');
      optionsInput.removeAttribute('required');
    }
  }

  // SweetAlert2 for Delete
  document.querySelectorAll('.action-delete').forEach(btn => {
    btn.addEventListener('click', function(){
      const form = this.closest('form');
      Swal.fire({
        title: 'Confirm Delete',
        text: 'Are you sure you want to delete this custom field? Existing responses will still show the data but the field will disappear from the form.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-lg border-0 ms-2',
          cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold py-2 px-4 rounded-lg border-0'
        },
        buttonsStyling: false
      }).then(result => {
        if(result.isConfirmed) form.submit();
      });
    });
  });
</script>
@endpush
