@extends('layouts.user_type.auth')


@section('content')
    <div class="container">
        <!-- Page Title -->
        <h2 class="text-center my-4">Stock Report</h2>

        <!-- Item Details Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Item Details</strong>
            </div>
            <div class="card-body py-3">
                <div class="row text-center text-md-start">
                    <div class="col-md-3">
                        <strong>Item Name:</strong> {{ $item->name ?? '' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Item Code:</strong> {{ $item->item_code ?? '' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Source Company:</strong> {{ $item->company->name ?? '' }}
                    </div>
                    <div class="col-md-2">
                        <strong>Alert Quantity:</strong> {{ $item->alert_quantity ?? '' }}
                    </div>
                    <div class="col-md-2">
                        <strong>Category:</strong> {{ $item->category->name ?? '' }}
                    </div>
                    <div class="col-md-2">
                        <strong>Total Balance:</strong> {{ $item->getTotalStockByItem($item->id) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Details Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <strong>Stock Details (by Expiry Date)</strong>
            </div>
            <div class="card-body p-2">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Expiry Date</th>
                            <th>Balance Stock</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($stock as $stk)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($stk->expiry_date)->format('d-m-Y') }}</td>

                                <td>{{ $stk->getTotalStock($stk->id) }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock Transactions Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <strong>Stock Transactions</strong>
            </div>
            <div class="card-body p-2">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($stockTransaction as $transaction)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>

                                <td> {{ $transaction->quantity??"" }}</td>
                                <td> {{ $transaction->status == 1 ? "Incoming" : "Outgoing" }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
