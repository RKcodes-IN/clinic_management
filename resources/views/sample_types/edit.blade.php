@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h4>Edit Sample Type</h4>

    <form action="{{ route('sample-types.update', $sampleType) }}" method="POST">
        @csrf
        @method('PUT')

        @include('sample_types.form')

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sample-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

