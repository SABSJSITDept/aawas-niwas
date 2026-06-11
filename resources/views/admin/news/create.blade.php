@extends('admin.layout')

@section('title', 'News Management')

@section('content')
<div class="max-w-7xl mx-auto">
  
  {{-- Header Section --}}
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-indigo-200">
        <i class="bi bi-megaphone-fill"></i>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-slate-800 m-0 tracking-tight">समाचार प्रबंधन</h2>
        <p class="text-slate-500 text-sm m-0 mt-1 font-medium">Manage news, updates and announcements</p>
      </div>
    </div>
    
    <div class="flex gap-2">
      @if(!isset($news))
      <button class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors border-0 flex items-center gap-2" onclick="document.getElementById('add-tab').click()">
        <i class="bi bi-plus-lg"></i> नई समाचार
      </button>
      @endif
      <a href="{{ route('admin.news.create') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors flex items-center gap-2 shadow-sm">
        <i class="bi bi-arrow-clockwise"></i> रिफ्रेश
      </a>
    </div>
  </div>

  {{-- Main Card --}}
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    
    {{-- Custom Tabs --}}
    <div class="bg-slate-50 border-b border-slate-200 px-4 pt-3 sm:px-6 sm:pt-4">
      <ul class="nav flex gap-2 border-0" id="newsTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link custom-tab {{ isset($news) ? '' : 'active' }}" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-news" type="button" role="tab" aria-controls="add-news" aria-selected="{{ isset($news) ? 'false' : 'true' }}">
            <i class="bi {{ isset($news) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>{{ isset($news) ? 'संपादित करें (Edit)' : 'नया समाचार (New)' }}
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link custom-tab {{ isset($news) ? 'active' : '' }}" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-news" type="button" role="tab" aria-controls="list-news" aria-selected="{{ isset($news) ? 'true' : 'false' }}">
            <i class="bi bi-list-ul me-2"></i>सभी समाचार (All News)
          </button>
        </li>
      </ul>
    </div>

    <div class="p-4 sm:p-6 lg:p-8">
      <div class="tab-content" id="newsTabContent">

        {{-- Add/Edit News Tab --}}
        <div class="tab-pane fade {{ isset($news) ? '' : 'show active' }}" id="add-news" role="tabpanel" aria-labelledby="add-tab">

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

          @if(isset($news))
            {{-- Edit Mode Alert --}}
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
                <span>आप <strong>"{{ $news->title }}"</strong> को संपादित कर रहे हैं।</span>
              </div>
              <a href="{{ route('admin.news.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold bg-blue-100 hover:bg-blue-200 px-3 py-1.5 rounded-lg transition-colors">
                रद्द करें (Cancel)
              </a>
            </div>
            <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data" id="newsForm" novalidate>
              @csrf
              @method('PUT')
          @else
            {{-- Create Mode --}}
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="newsForm" novalidate>
              @csrf
          @endif

              <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                {{-- Title --}}
                <div class="md:col-span-8">
                  <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">शीर्षक (Title) <span class="text-rose-500">*</span></label>
                  <input type="text" id="title" name="title" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700 font-medium" required
                         value="{{ old('title', isset($news) ? $news->title : '') }}" placeholder="समाचार का शीर्षक यहाँ लिखें...">
                </div>

                {{-- Status --}}
                <div class="md:col-span-4">
                  <label class="block text-sm font-semibold text-slate-700 mb-2">स्थिति (Status)</label>
                  <div class="flex items-center h-[50px] px-4 rounded-xl border border-slate-200 bg-slate-50">
                    <div class="form-check form-switch m-0 flex items-center gap-3">
                      <input class="form-check-input w-10 h-5 cursor-pointer" type="checkbox" id="is_active" name="is_active" value="1"
                             {{ old('is_active', isset($news) ? $news->is_active : true) ? 'checked' : '' }}>
                      <label class="form-check-label cursor-pointer text-sm font-semibold text-slate-700 pt-1" for="is_active">सक्रिय (Active)</label>
                    </div>
                  </div>
                </div>

                {{-- Content --}}
                <div class="md:col-span-12">
                  <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">विवरण (Content)</label>
                  <textarea name="content" id="content" rows="6" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-slate-700" placeholder="विस्तृत जानकारी यहाँ लिखें...">{{ old('content', isset($news) ? $news->content : '') }}</textarea>
                </div>

                {{-- Image Upload --}}
                <div class="md:col-span-6">
                  <label class="block text-sm font-semibold text-slate-700 mb-2">तस्वीर (Image) <span class="text-slate-400 font-normal">- Optional</span></label>
                  
                  <div class="relative">
                    <input type="file" id="image" name="image" class="hidden" accept="image/*">
                    <label for="image" class="w-full flex items-center justify-center gap-2 px-4 py-8 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 hover:bg-slate-100 hover:border-indigo-400 cursor-pointer transition-all group">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                        </div>
                        <div class="text-slate-600 font-medium">Click to upload image</div>
                    </label>
                  </div>
                  <p class="text-xs text-slate-500 mt-2"><i class="bi bi-info-circle"></i> Recommended: 1200x800px (JPG/PNG). Max 1MB.</p>
                </div>

                {{-- Image Preview / Current Image --}}
                <div class="md:col-span-6 flex flex-col justify-center">
                  
                  @if(isset($news) && $news->image)
                    <div id="currentImageContainer" class="mb-4">
                      <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Current Image</label>
                      <div class="relative inline-block rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                        <img src="{{ asset($news->image) }}" alt="Current" class="h-32 w-auto object-cover">
                      </div>
                    </div>
                  @endif

                  <div id="previewContainer" class="d-none">
                    <label class="block text-xs font-bold text-indigo-400 uppercase tracking-wider mb-2">New Preview</label>
                    <div class="relative inline-block rounded-xl overflow-hidden border border-indigo-200 shadow-sm group">
                      <img id="imagePreview" src="#" alt="Preview" class="h-32 w-auto object-cover">
                      <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                          <button type="button" id="removeImage" class="bg-white text-rose-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-rose-50 hover:scale-110 transition-all border-0 shadow-sm">
                              <i class="bi bi-trash-fill"></i>
                          </button>
                      </div>
                    </div>
                  </div>
                  
                </div>

                {{-- Actions --}}
                <div class="md:col-span-12 mt-4 pt-6 border-t border-slate-100 flex flex-wrap gap-3">
                  <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md shadow-indigo-200 flex items-center gap-2 border-0">
                    <i class="bi {{ isset($news) ? 'bi-check2-circle' : 'bi-send-fill' }}"></i> 
                    {{ isset($news) ? 'अपडेट करें (Update)' : 'सेव करें (Save)' }}
                  </button>
                  
                  @if(isset($news))
                    <a href="{{ route('admin.news.create') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                      रद्द करें (Cancel)
                    </a>
                  @else
                    <button type="reset" id="resetBtn" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                      रीसेट (Reset)
                    </button>
                  @endif
                </div>

              </div>
            </form>
        </div>

        {{-- List News Tab --}}
        <div class="tab-pane fade {{ isset($news) ? 'show active' : '' }}" id="list-news" role="tabpanel" aria-labelledby="list-tab">
          
          {{-- Toolbar --}}
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="relative w-full sm:w-96">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-search text-slate-400"></i>
              </div>
              <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm font-medium text-slate-700" placeholder="शीर्षक या विवरण से खोजें...">
            </div>
            
            <button class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors border-0 flex items-center gap-2" onclick="document.getElementById('add-tab').click()">
              <i class="bi bi-plus-lg"></i> नया समाचार
            </button>
          </div>

          {{-- Table --}}
          <div class="overflow-x-auto bg-white border border-slate-200 rounded-2xl shadow-sm">
            @if($allNews->isEmpty())
              <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-3xl mb-4">
                  <i class="bi bi-inbox"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-1">कोई समाचार नहीं मिला</h3>
                <p class="text-slate-500 text-sm">शुरू करने के लिए एक नया समाचार जोड़ें।</p>
              </div>
            @else
              <table class="w-full text-left border-collapse" id="newsTable">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                    <th class="p-4 w-[35%]">शीर्षक (Title)</th>
                    <th class="p-4 w-[20%] text-center">तस्वीर (Image)</th>
                    <th class="p-4 w-[15%] text-center">स्थिति (Status)</th>
                    <th class="p-4 w-[30%] text-center">क्रियाएँ (Actions)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($allNews as $item)
                  <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="p-4">
                      <div class="font-bold text-slate-800 mb-1">{{ $item->title }}</div>
                      <div class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ strip_tags($item->content) }}</div>
                    </td>
                    <td class="p-4 text-center">
                      @if($item->image)
                        <div class="w-16 h-12 mx-auto rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                          <img src="{{ asset($item->image) }}" alt="image" class="w-full h-full object-cover">
                        </div>
                      @else
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-slate-100 text-slate-400" title="No Image">
                          <i class="bi bi-image text-xl"></i>
                        </span>
                      @endif
                    </td>
                    <td class="p-4 text-center">
                      @if($item->is_active)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">
                          <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                        </span>
                      @endif
                    </td>
                    <td class="p-4">
                      <div class="flex items-center justify-center gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">

                        {{-- Toggle Status --}}
                        <form action="{{ route('admin.news.toggle', $item->id) }}" method="POST" class="m-0 toggle-form">
                          @csrf
                          @method('PATCH')
                          <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center border-0 transition-all action-toggle shadow-sm {{ $item->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100 hover:scale-105' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:scale-105' }}"
                                  data-id="{{ $item->id }}" data-active="{{ $item->is_active ? 1 : 0 }}" title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="bi {{ $item->is_active ? 'bi-pause-circle-fill' : 'bi-play-circle-fill' }}"></i>
                          </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 hover:scale-105 transition-all shadow-sm" title="Edit">
                          <i class="bi bi-pencil-square"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="m-0 delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-100 hover:scale-105 transition-all border-0 shadow-sm action-delete" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                          </button>
                        </form>

                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

              {{-- Pagination --}}
              @if($allNews->hasPages())
              <div class="p-4 border-t border-slate-200 flex justify-center">
                {{ $allNews->links() }}
              </div>
              @endif

            @endif
          </div>

        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* Custom Tabs Styling */
  .custom-tab {
    background: transparent !important;
    border: none !important;
    color: #64748b;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.25rem !important;
    border-bottom: 2px solid transparent !important;
    border-radius: 0 !important;
    transition: all 0.2s;
  }
  .custom-tab:hover {
    color: #4f46e5;
  }
  .custom-tab.active {
    color: #4f46e5 !important;
    border-bottom: 2px solid #4f46e5 !important;
  }
  
  /* Make table look nice on mobile */
  @media (max-width: 640px) {
    table, thead, tbody, th, td, tr { display: block; }
    thead tr { position: absolute; top: -9999px; left: -9999px; }
    tr { border-bottom: 1px solid #e2e8f0; margin-bottom: 1rem; }
    td { border: none; position: relative; padding-left: 50% !important; text-align: left !important; }
    td:before { position: absolute; top: 1rem; left: 1rem; width: 45%; padding-right: 10px; white-space: nowrap; font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; }
    td:nth-of-type(1):before { content: "शीर्षक"; }
    td:nth-of-type(2):before { content: "तस्वीर"; }
    td:nth-of-type(3):before { content: "स्थिति"; }
    td:nth-of-type(4):before { content: "क्रियाएँ"; }
    td:nth-of-type(4) .flex { justify-content: flex-start; }
  }
</style>
@endpush

@push('scripts')
<script>
  // Image preview & remove (improved)
  (function(){
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImage = document.getElementById('removeImage');
    const currentImageContainer = document.getElementById('currentImageContainer');

    if(imageInput){
      imageInput.addEventListener('change', function(){
        const file = this.files[0];
        if(file){
          const reader = new FileReader();
          reader.onload = function(e){
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('d-none');
            if(currentImageContainer) currentImageContainer.classList.add('hidden');
          }
          reader.readAsDataURL(file);
        } else {
          previewContainer.classList.add('d-none');
          imagePreview.src = '#';
          if(currentImageContainer) currentImageContainer.classList.remove('hidden');
        }
      });
    }

    if(removeImage){
      removeImage.addEventListener('click', function(e){
        e.preventDefault();
        imageInput.value = null;
        previewContainer.classList.add('d-none');
        imagePreview.src = '#';
        if(currentImageContainer) currentImageContainer.classList.remove('hidden');
      });
    }
  })();

  // Client-side search with debounce
  (function(){
    const searchInput = document.getElementById('searchInput');
    let timeout = null;
    if(searchInput){
      searchInput.addEventListener('input', function(){
        clearTimeout(timeout);
        const q = this.value.toLowerCase();
        timeout = setTimeout(() => {
          const rows = document.querySelectorAll('#newsTable tbody tr');
          rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            if(text.indexOf(q) > -1){
              r.style.display = '';
            } else {
              r.style.display = 'none';
            }
          });
        }, 200);
      });
    }
  })();

  // SweetAlert2 confirmations for delete & toggle
  (function(){
    // Delete
    document.querySelectorAll('.action-delete').forEach(btn => {
      btn.addEventListener('click', function(){
        const form = this.closest('form');
        Swal.fire({
          title: 'Confirm Delete',
          text: 'क्या आप वाकई इस समाचार को हटाना चाहते हैं? यह वापस नहीं होगा।',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'हाँ, हटाएँ (Yes, Delete)',
          cancelButtonText: 'नहीं, रद्द करें (Cancel)',
          customClass: {
            confirmButton: 'bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-lg border-0 ms-2',
            cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold py-2 px-4 rounded-lg border-0'
          },
          buttonsStyling: false
        }).then(result => {
          if(result.isConfirmed){
            form.submit();
          }
        });
      });
    });

    // Toggle active/inactive
    document.querySelectorAll('.action-toggle').forEach(btn => {
      btn.addEventListener('click', function(){
        const active = this.dataset.active === '1';
        const form = this.closest('form');

        Swal.fire({
          title: active ? 'Deactivate News?' : 'Activate News?',
          text: active ? 'यह समाचार वेबसाइट से छिप जाएगा।' : 'यह समाचार वेबसाइट पर दिखने लगेगा।',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: active ? 'हाँ, Deactivate करें' : 'हाँ, Activate करें',
          cancelButtonText: 'रद्द करें (Cancel)',
          customClass: {
            confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg border-0 ms-2',
            cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold py-2 px-4 rounded-lg border-0'
          },
          buttonsStyling: false
        }).then(result => {
          if(result.isConfirmed){
            form.submit();
          }
        });
      });
    });
  })();
</script>
@endpush
