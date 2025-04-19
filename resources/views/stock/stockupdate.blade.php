@extends('layouts.user_type.auth')
@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow p-4">
            <h3 class="mb-4">Import Stock Update</h3>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label for="excel_file">Choose Excel File (.xlsx or .csv)</label>
                    <input type="file" name="excel_file" class="form-control" required >
                </div>

                <button type="submit" class="btn btn-primary">Import Stock</button>
            </form>
        </div>
    </div>
@endsection
