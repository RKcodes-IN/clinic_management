@extends('layouts.user_type.auth')

@section('content')
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <h3>Send Update Form Mesage</h3>
    <form action="{{ route('updateform.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Send</button>
    </form>
    </body>

    </html>
@endsection
