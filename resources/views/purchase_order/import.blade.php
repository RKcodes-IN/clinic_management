@extends('layouts.user_type.auth')

@section('content')
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <h3>Purchase Order Import</h3>

    <form action="{{ route('purchaseorder.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Purchase Order</button>
    </form>
    <h3>Purchase Order Item Import</h3>
    <form action="{{ route('purchaseorderItem.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Purchase Order Item</button>
    </form>


    </body>

    </html>
@endsection
