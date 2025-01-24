@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Sample Type Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $sampleType->name }}</h5>
            <p class="card-text">
                Status: {{ $sampleType->status ? 'Active' : 'Inactive' }}
            </p>
            <a href="{{ route('sample-types.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
