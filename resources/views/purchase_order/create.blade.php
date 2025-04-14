@extends('layouts.user_type.auth')

@section('content')
    <style>
        input.form.form-check-input.item-checkbox.border-2.bg-light {
            border: solid 1px #504f4f !important;
        }
    </style>
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
                        @if (isset($items) && $items->isNotEmpty())
                            <form action="{{ route('purchase_order.createPurchaseOrder') }}" method="POST">
                                @csrf
                                <!-- Hidden input to pass selected company ID -->
                                <input type="hidden" name="source_company_id"
                                    value="{{ old('source_company_id', request('source_company_id')) }}">

                                <div class="form-group mt-4">
                                    <label>Items</label><br>

                                    <input type="checkbox" id="selectAll" /> <strong>Select All</strong>
                                    <button type="submit" class="btn btn-success ">Next</button>

                                    <table class="table table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Item Name</th>
                                                <th>ideal.Q</th>
                                                <th>Avl.Q</th>
                                                <th>Alert Q.</th>

                                                <th>Reorder Q.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>
                                                        <!-- Ensure the checkbox input is rendered correctly -->
                                                        <input type="checkbox"
                                                            class="form form-check-input item-checkbox border-2 bg-light"
                                                            name="items[]" value="{{ $item->id }}">
                                                    </td>
                                                    @if (App\models\Item::getTotalStockByItem($item->id) <= $item->alert_quantity)
                                                        <td style="text-wrap: auto !important; color: red;">
                                                            {{ $item->name }}</td>
                                                    @else
                                                        <td style="text-wrap: auto !important;">{{ $item->name }}</td>
                                                    @endif
                                                    <td>{{ $item->ideal_quantity }}</td>
                                                    <td>{{ App\models\Item::getTotalStockByItem($item->id) }}</td>
                                                    <td>{{ $item->alert_quantity }}</td>

                                                    <td>{{ $item->reorder_quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="invoice_number">Invoice Number</label>
                                        <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                                            placeholder="Invoice Number" required>
                                    </div>
                                    <div class="col-6">


                                        <button type="submit" class="btn btn-success ">Next</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p>No items found for the selected company.</p>
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
