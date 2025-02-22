@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            @if (session('success'))
                <p>{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('import.rack') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls">
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
@endsection

