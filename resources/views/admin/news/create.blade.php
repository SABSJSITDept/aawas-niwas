@extends('admin.layout')

@section('content')
<div class="container mt-5">
  <div class="card shadow-sm border-0">
    {{-- Header --}}
    <div class="card-header p-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between"
         style="background: linear-gradient(90deg,#0d6efd 0%, #6610f2 100%);">
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
          <span class="fs-4">📰</span>
        </div>
        <div class="text-white">
          <div class="fs-5 fw-bold mb-0" style="color:#000 !important;">समाचार प्रबंधन</div>
          <small class="d-block" style="color:#000 !important; opacity:0.75;">(News Management)</small>
        </div>
      </div>

      <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="#add-news" class="btn btn-light btn-sm fw-semibold" data-bs-toggle="tab" role="tab" aria-controls="add-news">
          <i class="fas fa-plus me-1"></i> नई समाचार जोड़ें
        </a>
        <a href="{{ route('admin.news.create') }}" class="btn btn-outline-light btn-sm fw-semibold d-none d-md-inline-flex" title="Open create page">
          <i class="fas fa-external-link-alt me-1"></i> नया पेज खोलें
        </a>
      </div>
    </div>

    <div class="card-body">
      {{-- Tabs --}}
      <ul class="nav nav-tabs mb-4" id="newsTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ isset($news) ? '' : 'active' }}" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-news" type="button"
                  role="tab" aria-controls="add-news" aria-selected="{{ isset($news) ? 'false' : 'true' }}">📝 {{ isset($news) ? 'संपादित करें' : 'नया' }}</button>
        </li>
        <li class="nav-item ms-2" role="presentation">
          <button class="nav-link {{ isset($news) ? 'active' : '' }}" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-news" type="button"
                  role="tab" aria-controls="list-news" aria-selected="{{ isset($news) ? 'true' : 'false' }}">📋 सभी समाचार</button>
        </li>
      </ul>

      <div class="tab-content" id="newsTabContent">

        {{-- Add/Edit News Tab --}}
        <div class="tab-pane fade {{ isset($news) ? '' : 'show active' }}" id="add-news" role="tabpanel" aria-labelledby="add-tab">

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(isset($news))
            {{-- Edit Mode --}}
            <div class="alert alert-info">
              <i class="fas fa-edit me-1"></i> आप समाचार "<strong>{{ $news->title }}</strong>" को संपादित कर रहे हैं।
              <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-outline-secondary ms-2">रद्द करें</a>
            </div>
            <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data" id="newsForm" novalidate>
              @csrf
              @method('PUT')
          @else
            {{-- Create Mode --}}
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="newsForm" novalidate>
              @csrf
          @endif

              <div class="row g-3">
                <div class="col-md-8">
                  <label for="title" class="form-label fw-semibold">शीर्षक <span class="text-danger">*</span></label>
                  <input type="text" id="title" name="title" class="form-control form-control-lg" required
                         value="{{ old('title', isset($news) ? $news->title : '') }}" placeholder="समाचार का संक्षिप्त शीर्षक डालें">
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold d-block">स्थिति</label>
                  <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', isset($news) ? $news->is_active : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">सक्रिय (Active)</label>
                  </div>
                </div>

                <div class="col-12">
                  <label for="content" class="form-label fw-semibold">विवरण</label>
                  <textarea name="content" id="content" class="form-control" rows="6" placeholder="समाचार का विस्तृत विवरण (यदि आवश्यक हो)">{{ old('content', isset($news) ? $news->content : '') }}</textarea>
                </div>

                <div class="col-md-6">
                  <label for="image" class="form-label fw-semibold">तस्वीर (optional)</label>
                  <input type="file" id="image" name="image" class="form-control" accept="image/*" aria-describedby="imageHelp">
                  <div id="imageHelp" class="form-text">अनुशंसित आकार: 1200x800px (jpg/png). अधिकतम 1MB.</div>
                  @if(isset($news) && $news->image)
                    <div class="mt-2">
                      <small class="text-muted">वर्तमान तस्वीर:</small>
                      <img src="{{ asset($news->image) }}" alt="Current" class="img-thumbnail d-block mt-1" style="max-height:100px;">
                    </div>
                  @endif
                </div>

                <div class="col-md-6 d-flex align-items-center">
                  <div id="previewContainer" class="ms-3 d-none">
                    <label class="form-label fw-semibold mb-1">पूर्वावलोकन</label>
                    <div class="d-flex align-items-center gap-2">
                      <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height:100px;">
                      <button type="button" id="removeImage" class="btn btn-sm btn-outline-danger">हटाएँ</button>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                      🔸 {{ isset($news) ? 'समाचार अपडेट करें' : 'समाचार सेव करें' }}
                    </button>
                    @if(isset($news))
                      <a href="{{ route('admin.news.create') }}" class="btn btn-outline-secondary btn-lg">रद्द करें</a>
                    @else
                      <button type="reset" id="resetBtn" class="btn btn-outline-secondary btn-lg">रीसेट</button>
                    @endif
                  </div>
                </div>
              </div>
            </form>
        </div>

        {{-- List News Tab --}}
        <div class="tab-pane fade {{ isset($news) ? 'show active' : '' }}" id="list-news" role="tabpanel" aria-labelledby="list-tab">
          <div class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="input-group w-100 w-md-50">
              <span class="input-group-text">🔎</span>
              <input type="text" id="searchInput" class="form-control" placeholder="शीर्षक या वर्णन से खोजें..." aria-label="Search news">
            </div>

            <div class="d-flex gap-2 ms-auto">
              <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> नया समाचार
              </a>
            </div>
          </div>

          <div class="table-responsive shadow-sm rounded">
            @if($allNews->isEmpty())
              <div class="p-4 text-center text-muted">कोई समाचार नहीं मिला।</div>
            @else
              <table class="table table-hover align-middle mb-0" id="newsTable">
                <thead class="table-light">
                  <tr class="small text-uppercase">
                    <th style="width:35%">शीर्षक</th>
                    <th style="width:20%">तस्वीर</th>
                    <th style="width:15%">स्थिति</th>
                    <th style="width:30%">क्रियाएँ</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($allNews as $item)
                  <tr>
                    <td class="text-start">
                      <div class="fw-semibold">{{ $item->title }}</div>
                      <div class="text-muted small">{{ Str::limit(strip_tags($item->content), 80) }}</div>
                    </td>
                    <td class="text-center">
                      @if($item->image)
                        <img src="{{ asset($item->image) }}" alt="image" class="rounded" style="max-height:70px; object-fit:cover;">
                      @else
                        <span class="text-muted fst-italic">कोई तस्वीर नहीं</span>
                      @endif
                    </td>
                    <td>
                      @if($item->is_active)
                        <span class="badge bg-success">Active</span>
                      @else
                        <span class="badge bg-secondary">Inactive</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">

                        {{-- Toggle Active/Inactive --}}
                        <form action="{{ route('admin.news.toggle', $item->id) }}" method="POST" class="d-inline toggle-form">
                          @csrf
                          @method('PATCH')
                          <button type="button" class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }} action-toggle"
                                  data-id="{{ $item->id }}" data-active="{{ $item->is_active ? 1 : 0 }}">
                            {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                          </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-info text-white">
                          <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-danger action-delete">
                            <i class="fas fa-trash"></i> Delete
                          </button>
                        </form>

                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

              <div class="d-flex justify-content-center mt-3">
                {{ $allNews->links() }}
              </div>

            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Image preview & remove (improved)
  (function(){
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImage = document.getElementById('removeImage');

    if(imageInput){
      imageInput.addEventListener('change', function(){
        const file = this.files[0];
        if(file){
          const reader = new FileReader();
          reader.onload = function(e){
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('d-none');
          }
          reader.readAsDataURL(file);
        } else {
          previewContainer.classList.add('d-none');
          imagePreview.src = '#';
        }
      });
    }

    if(removeImage){
      removeImage.addEventListener('click', function(){
        imageInput.value = null;
        previewContainer.classList.add('d-none');
        imagePreview.src = '#';
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
            r.style.display = text.indexOf(q) > -1 ? '' : 'none';
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
          title: 'Confirm delete',
          text: 'क्या आप वाकई इस समाचार को स्थायी रूप से हटाना चाहते हैं? यह क्रिया वापस नहीं होगी।',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'हाँ, हटाएँ',
          cancelButtonText: 'नहीं, रद्द करें'
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
        const id = this.dataset.id;
        const active = this.dataset.active === '1';
        const form = this.closest('form');

        Swal.fire({
          title: active ? 'Deactivate news?' : 'Activate news?',
          text: active ? 'यह समाचार डीएक्टिवेट हो जाएगा।' : 'यह समाचार सक्रिय हो जाएगा।',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: active ? 'हाँ, डीएक्टिवेट करें' : 'हाँ, एक्टिवेट करें',
          cancelButtonText: 'रद्द करें'
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
