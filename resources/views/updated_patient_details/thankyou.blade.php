@extends('layouts.user_type.guest')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <!-- Success Icon (requires Font Awesome; if not installed, you can replace it with any icon/image) -->
                <div class="mb-3">
                    <i class="fa fa-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                </div>
                <h2 class="mb-3">Thank You!</h2>
                <p class="lead">{{ $message }}</p>
                <!-- Optionally, include a button to go back home or to another page -->
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
            </div>
        </div>
    </div>
@endsection
