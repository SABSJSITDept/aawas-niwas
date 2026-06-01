@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-file-alt"></i> चेक-इन / चेक-आउट रिपोर्ट</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('room.report.fetch') }}" method="POST" class="row g-3 mb-4">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Check-in Date</label>
                            <input type="date" name="check_in_date" class="form-control form-control-lg" >
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Check-out Date</label>
                            <input type="date" name="check_out_date" class="form-control form-control-lg" >
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success btn-lg w-100"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </form>

                    @isset($data)
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('room.report.pdf', request()->all()) }}" class="btn btn-outline-danger btn-lg"><i class="fas fa-download"></i> Download PDF</a>
                    </div>

                    <p class="mb-3">Total Entries: {{ count($data) }}</p>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> Sr. No.</th>
                                    <th><i class="fas fa-user"></i> नाम</th>
                                    <th><i class="fas fa-phone"></i> फोन</th>
                                    <th><i class="fas fa-users"></i> कुल व्यक्ति</th>
                                    <th><i class="fas fa-calendar-check"></i> चेक-इन</th>
                                    <th><i class="fas fa-clock"></i> चेक-इन टाइम</th>
                                    <th><i class="fas fa-check-circle"></i> स्टेटस</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['phone'] }}</td>
                                        <td class="text-center">{{ $row['total_persons'] }}</td>
                                        <td>{{ $row['check_in_date'] }}</td>
                                        <td>{{ $row['check_in_time'] ?? '-' }}</td>
                                        <td>{{ ucfirst($row['status'] ?? '-') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle"></i> कोई रिकॉर्ड नहीं मिला।
                                        </td>
                                    </tr>
                                @endforelse

                                @if(!empty($data) && count($data) > 0)
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="4">कुल</td>
                                        <td class="text-center">{{ array_sum(array_column($data, 'total_persons')) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
