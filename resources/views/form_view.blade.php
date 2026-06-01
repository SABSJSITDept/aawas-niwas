    
    @extends('admin.layout')
    @section('content')
  
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">Applied data for Aawas Niwas (Management)</h5>
                        <p class="card-text">Data For Aawas Niwas (Management).</p>
                        <a href="{{ route('admin.forms') }}" class="btn btn-primary btn-lg">See Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">Applied data for Aawas Niwas (दर्शनार्थियों)</h5>
                        <p class="card-text">Data For Aawas Niwas (Management).</p>
                        <a href="{{ route('form_data_dar') }}" class="btn btn-primary btn-lg">See Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endsection
