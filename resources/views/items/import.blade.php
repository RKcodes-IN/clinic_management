@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            @if (session('success'))
                <p>{{ session('success') }}</p>
            @endif

            <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit">Import Items</button>
            </form>
        </div>
    </div>
@endsection
