@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h4>Create Purchase Order</h4>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('updatePurchaseOrderItems', ['purchase_order_id' => $purchaseOrder->id]) }}" method="POST">
        @csrf
        <div class="card text-left">
        <table class="table table-striped table-inverse table-responsive">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Alert Quantity</th>
                    <th>Ideal Quantity</th>
                    <th>Reorder Quantity</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->purchaseOrderItems as $orderItem)
                    <tr>
                        <td>{{ $orderItem->item->item_code }}</td>
                        <td>{{ $orderItem->item->name }}</td>

                        <td>{{ $orderItem->item->alert_quantity }}</td>

                        <td>{{ $orderItem->item->ideal_quantity }}</td>

                        <td>{{ $orderItem->item->reorder_quantity }}</td>

                        <td>
                            <input type="number" name="items[{{ $orderItem->item->id }}]"
                                   value="{{ $orderItem->quantity ?? 0 }}"
                                   class="form-control" min="1">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
       <p class="text-center">
        <button type="submit" class="btn btn-primary mt-3">Create Purchase Order</button>
        </p>
    </form>
</div>
@endsection
