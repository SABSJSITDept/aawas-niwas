@extends('admin.layout')

@section('content')

<style>
    /* Card hover effect */
    .hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Button hover effect */
    .hover-button {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .hover-button:hover {
        background-color: #000;
        color: #fff;
    }

    /* Increased font sizes */
    .card-title {
        font-size: 1.8rem;
        color: #1a1a1a;
    }

    .card-text {
        font-size: 1.2rem;
        color: #1a1a1a;
    }

    .btn-lg {
        padding: 12px 35px;
        font-size: 1.25rem;
    }
</style>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <!-- Personal/Family Card -->
        <div class="col-md-6 mb-4">
            <div class="card hover-effect shadow-sm border-0 rounded-lg bg-warning">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Applied data for Aawas Niwas (Personal/Family)</h5>
                    <p class="card-text">Data For Aawas Niwas (Personal/Family).</p>
                    <a href="{{ route('family-booking.index') }}" class="btn btn-dark btn-lg hover-button">See Now</a>
                </div>
            </div>
        </div>

        <!-- Group/Sangh Card -->
        <div class="col-md-6 mb-4">
            <div class="card hover-effect shadow-sm border-0 rounded-lg bg-info">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Applied data for Aawas Niwas (Group/sangh)</h5>
                    <p class="card-text">Data For Aawas Niwas (Group/sangh).</p>
                    <a href="{{ route('group.booking.create') }}" class="btn btn-dark btn-lg hover-button">See Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
