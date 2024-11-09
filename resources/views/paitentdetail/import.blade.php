@extends('layouts.user_type.auth')

@section('content')
    
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('patient.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Paitents</button>
    </form>
</body>
</html>
@endsection
