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
                <div class="table-responsive">
                    <table class="table table-striped table-inverse">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Ordering Name</th>
                                <th>Alert <br> Qty</th>
                                <th>Ideal <br> Qty</th>
                                <th>Reord<br> Qty</th>
                                <th>UOM</th>
                                <th>conv. <br> Ratio</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrder->purchaseOrderItems as $orderItem)
                                <tr>
                                    <td style="font-size: 12px !important;">{{ $orderItem->item->item_code ?? '' }}</td>
                                    <td style="text-wrap: auto !important;" >{{ $orderItem->item->poitem_name ?? '' }}</td>
                                    <td>{{ $orderItem->item->alert_quantity ?? '' }}</td>
                                    <td>{{ $orderItem->item->ideal_quantity ?? 0 }}</td>
                                    <td>{{ $orderItem->item->reorder_quantity ?? '' }}</td>
                                    <td>{{ $orderItem->item->pouom->name ?? 0 }}</td>
                                    <td>{{ $orderItem->item->unit_conversion_ratio ?? '' }}</td>
                                    <td>
                                        <input type="text" name="items[{{ $orderItem->item->id }}]" value="{{ $orderItem->quantity ?? 0 }}"
                                            class="form-control table-input">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="text-center">
                <button type="submit" class="btn btn-primary mt-3">Update Order</button>
            </p>
        </form>
    </div>

    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th {
            white-space: nowrap;
            background-color: #343a40;
            color: white;
        }

        .table td {
            vertical-align: middle;
            padding: 5px;
        }

        .table-input {
            max-width: 70px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 0.4rem;
            }

            .table-input {
                max-width: 60px;
            }

            h4 {
                font-size: 1.25rem;
            }


        }
    </style>
@endsection
