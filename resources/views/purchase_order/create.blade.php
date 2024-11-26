@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Purchase Order</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Form to select source company -->
                        <form action="{{ route('purchase_order.fetch_items') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="source_company">Source Company</label>
                                <select name="source_company_id" class="form-control" required>
                                    <option value="">Select Source Company</option>
                                    @foreach ($sourceCompanies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Get Items</button>
                        </form>

                        <!-- Display items after selecting source company -->
                        @if(isset($items) && $items->isNotEmpty())
                            <form action="{{ route('purchase_order.createPurchaseOrder') }}" method="POST">
                                @csrf
                                <!-- Hidden input to pass selected company ID -->
                                <input type="hidden" name="source_company_id" value="{{ old('source_company_id', request('source_company_id')) }}">
                                
                                <div class="form-group mt-4">
                                    <label>Items</label><br>
                                    <input type="checkbox" id="selectAll" /> Select All
                                    <div class="form-check">
                                        @foreach ($items as $item)
                                            <input type="checkbox" class="form-check-input item-checkbox" name="items[]" value="{{ $item->id }}">
                                            <label class="form-check-label">{{ $item->name }}</label><br>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Create Purchase Order</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for "Select All" functionality
        document.getElementById('selectAll').addEventListener('click', function(event) {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        });
    </script>
@endsection
