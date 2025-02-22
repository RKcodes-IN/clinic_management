@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h2>Update Pricing</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('stock.updatePrice') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="item">Item</label>
                <select id="item" name="item_id" class="form-control select2" required>
                    <option value="" disabled selected>Select an item</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="Enter price" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>



            <button type="submit" class="btn btn-primary">Update Pricing</button>
        </form>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an item",
            allowClear: true
        });
    });
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
