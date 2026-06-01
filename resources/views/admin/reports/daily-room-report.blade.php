@extends('admin.layout')

@section('content')
<div class="container py-5">
    <h2 class="text-center text-primary fw-bold mb-4">📊 All Daily Room Report</h2>

    <!-- 🔍 Date Filter Form -->
    <form method="GET" action="{{ route('admin.daily-room-report') }}" class="row mb-4">
        <div class="col-md-4">
            <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="form-control">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary">🔍 Search</button>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.daily-room-report.download', ['date' => request('date', date('Y-m-d'))]) }}" class="btn btn-sm btn-danger">
                ⬇️ PDF डाउनलोड करें
            </a>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-info text-white p-3 shadow-sm">
                <h5>🛏️ कुल रूम:</h5>
                <h3>{{ $totalRooms }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white p-3 shadow-sm">
                <h5>📕 बुक रूम:</h5>
                <h3>{{ $bookedRooms }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 shadow-sm">
                <h5>📗 खाली रूम:</h5>
                <h3>{{ $emptyRooms }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-white p-3 shadow-sm">
                <h5>📦 कुल क्षमता:</h5>
                <h3>{{ $totalCapacity }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3 shadow-sm">
                <h5>📦 बुक्ड क्षमता:</h5>
                <h3>{{ $bookedCapacity }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white p-3 shadow-sm">
                <h5>📦 उपलब्ध क्षमता:</h5>
                <h3>{{ $availableCapacity }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light p-3 shadow-sm">
                <h5>👥 कुल गेस्ट आज तक:</h5>
                <h3>{{ $totalGuests }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 shadow-sm">
                <h5>🟢 आज के चेक-इन:</h5>
                <h3>{{ $checkInCount }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white p-3 shadow-sm">
                <h5>🔴 आज के चेक-आउट:</h5>
                <h3>{{ $checkOutCount }}</h3>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- ❌ Not Allotted Summary -->
    <div class="row mt-4">
        <div class="col-md-6">
            <h4>❌ Room Not Allotted Total Persons (Pending Bookings):</h4>
            <p class="fs-4 text-danger fw-bold">👥 {{ $notAllottedCount }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daily-room-report.notallotted-pdf') }}" class="btn btn-outline-dark">
                📥 Download Not Allotted Report (PDF)
            </a>
        </div>
    </div>
</div>
@endsection
