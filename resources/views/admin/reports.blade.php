@extends('admin.layout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-primary">🗂 रिपोर्ट्स</h2>
    <p class="text-muted mb-4">यहाँ से आप सभी प्रकार की रिपोर्ट्स देख सकते हैं।</p>

    <div class="row g-4">     

        <!-- फीडबैक रिपोर्ट -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-warning text-dark fw-bold">💬 फीडबैक</div>
                <div class="card-body">
                    <p>प्राप्त सभी फीडबैक्स देखने के लिए नीचे क्लिक करें।</p>
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-sm btn-outline-warning">➡️ देखें</a>
                </div>
            </div>
        </div>

        <!-- होटल जानकारी देखें -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-secondary text-white fw-bold">ℹ️ होटल जानकारी</div>
                <div class="card-body">
                    <p>सभी होटलों की विस्तृत जानकारी देखें।</p>
                    <a href="{{ route('select.hotel') }}" class="btn btn-sm btn-outline-secondary">➡️ देखें</a>
                </div>
            </div>
        </div>

        <!-- Download Excel Report -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white fw-bold">📥 डाउनलोड एक्सेल</div>
                <div class="card-body">
                    <p>सभी होटलों की availability Excel रिपोर्ट डाउनलोड करें।</p>
                    <a href="{{ route('rooms.export.all') }}" class="btn btn-sm btn-outline-dark">⬇️ Download</a>
                </div>
            </div>
        </div>

        <!-- कमरे की सुविधाएं रिपोर्ट -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-bold">🛏️ कमरे की सुविधाएं</div>
                <div class="card-body">
                    <p>होटल के कमरों की सुविधाएं देखने के लिए क्लिक करें।</p>
                    <a href="{{ url('/admin/room-features-page') }}" class="btn btn-sm btn-outline-primary">➡️ देखें</a>
                </div>
            </div>
        </div>

        <!-- रूम रिपोर्ट -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white fw-bold">📋 रूम रिपोर्ट</div>
                <div class="card-body">
                    <p>बुक किए गए कमरों की रिपोर्ट देखने के लिए क्लिक करें।</p>
                    <a href="{{ route('admin.room.report') }}" class="btn btn-sm btn-outline-success">➡️ रिपोर्ट देखें</a>
                </div>
            </div>
        </div>

        <!-- फैमिली एक्सपोर्ट -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-dark fw-bold">👨‍👩‍👧‍👦 फैमिली एक्सपोर्ट</div>
                <div class="card-body">
                    <p>हेड और उनके सभी फैमिली मेंबर्स की एक्सेल रिपोर्ट डाउनलोड करें।</p>
                    <a href="{{ route('family.members.export') }}" class="btn btn-sm btn-outline-info">⬇️ डाउनलोड</a>
                </div>
            </div>
        </div>

        <!-- ग्रुप एक्सपोर्ट -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white fw-bold">👥 ग्रुप एक्सपोर्ट</div>
                <div class="card-body">
                    <p>ग्रुप लीडर और उनके सभी ग्रुप मेंबर्स की एक्सेल रिपोर्ट डाउनलोड करें।</p>
                    <a href="{{ route('group.members.export') }}" class="btn btn-sm btn-outline-danger">⬇️ डाउनलोड</a>
                </div>
            </div>
        </div>
<!-- बाकी कार्ड्स के नीचे यह सेक्शन जोड़ें -->

<!-- रूम बुकिंग डिटेल्ड रिपोर्ट (PDF) -->
<div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-primary text-white fw-bold">📑 डिटेल्ड रूम रिपोर्ट (PDF)</div>
        <div class="card-body">
            <p>चुने हुए होटल और तारीख के अनुसार सभी बुक्ड रूम्स की डिटेल PDF रिपोर्ट प्राप्त करें।</p>
            <a href="{{ route('admin.room.booking.report') }}" class="btn btn-sm btn-outline-primary">➡️ रिपोर्ट देखें</a>
        </div>
    </div>
</div>


<div class="row g-4">

    <!-- CHECK-IN & CHECK-OUT REPORT -->
    <div class="col-md-4">
        <div class="card shadow-lg border-0 h-100" style="background-color: #f4fdf9;">
            <div class="card-header fw-bold text-white" style="background-color: #198754;">
                📑 CHECK-IN & CHECK-OUT REPORT (PDF)
            </div>
            <div class="card-body">
                <p style="color: #333;">
                    चुने हुए होटल और तारीख के अनुसार सभी बुक्ड रूम्स की डिटेल PDF रिपोर्ट प्राप्त करें।
                </p>
                <a href="{{ route('room.checkin.report') }}" class="btn btn-sm" 
                   style="color: #198754; border: 1px solid #198754;">
                    ➡️ रिपोर्ट देखें
                </a>
            </div>
        </div>
    </div>

    <!-- डेली रिपोर्ट -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success text-white fw-bold">📅 डेली रिपोर्ट</div>
            <div class="card-body">
                <p>आज की तारीख में कितने लोग होटल में रुके हैं, उसकी रिपोर्ट देखें या PDF डाउनलोड करें।</p>
                <a href="{{ route('daily.report') }}" class="btn btn-sm btn-outline-success">➡️ देखें</a>
            </div>
        </div>
    </div>

    <!-- All Daily Room Report -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white fw-bold">📊 All Daily Room Report</div>
            <div class="card-body">
                <p>आज के हिसाब से कुल रूम, बुक रूम, खाली रूम, चेक-इन और चेक-आउट की डेली रिपोर्ट देखें।</p>
                <a href="{{ route('admin.daily-room-report') }}" class="btn btn-sm btn-outline-primary">
    ➡️ रिपोर्ट देखें
</a>

            </div>
        </div>
    </div>

    
<div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-primary text-white fw-bold">📈 Date-wise Check-in Report</div>
        <div class="card-body">
            <p>View how many guests checked in each day.</p>
            <a href="{{ route('admin.checkin.report') }}" class="btn btn-sm btn-outline-primary">➡️ View Report</a>
        </div>
    </div>
</div>


</div>


    </div>
</div>
@endsection

@push('styles')
<style>
    h2 {
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush
