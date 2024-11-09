@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <form action="{{ route('purchaseorders.store') }}" method="POST" class="row g-3">
            @csrf
            <!-- Item dropdown -->
            <div class="col-md-4">
                <label for="item_id" class="form-label">Item</label>
                <select class="form-control" id="item_id" name="item_id">
                    <option value="">Select an item</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} ({{ $item->item_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div class="col-md-4">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
            </div>

            <!-- Date -->
            <div class="col-md-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <!-- Status -->


            <!-- Submit button -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

<!-- Include Select2 for searchable dropdown -->
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#item_id').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });
    </script>
@endpush
