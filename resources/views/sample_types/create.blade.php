@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h4>Create New Sample Type</h4>

        <form action="{{ route('sample-types.store') }}" method="POST">
            @csrf

            @include('sample_types.form')

            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('sample-types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
