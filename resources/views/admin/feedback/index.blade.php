@extends('admin.layout')
@section('content')
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">📥 सभी फीडबैक</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($feedbacks->count())
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>नाम</th>
                    <th>ईमेल</th>
                    <th>मोबाईल</th>
                    <th>संदेश</th>
                    <th>तारीख</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $feedback->name }}</td>
                        <td>{{ $feedback->email }}</td>
                        <td>{{ $feedback->phone }}</td>
                        <td>{{ $feedback->message }}</td>
                        <td>{{ $feedback->created_at->format('d-m-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $feedbacks->links() }}
        </div>
    @else
        <p class="text-center">कोई फीडबैक मौजूद नहीं है।</p>
    @endif
</div>
@endsection
