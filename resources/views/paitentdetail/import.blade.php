@extends('layouts.user_type.auth')

@section('content')
    
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

<h3>Paitent Detail Import</h3>

    <form action="{{ route('patient.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Paitents</button>
    </form>
    <h3>Uom Type Import</h3>


    <form action="{{ route('uomtype.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Uom type</button>
    </form>
</body>
</html>
@endsection
