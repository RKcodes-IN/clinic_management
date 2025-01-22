@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-success">
                    Download PDF
                </a>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Invoice #{{ $invoice->invoice_number }}
                    </div>
                    <div class="card-body">
                        <!-- Invoice Summary -->
                        <h5>Invoice Summary</h5>
                        <form action="{{ route('invoice.updatePayment', $invoice->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <table class="table table-bordered">
                                <tr>
                                    <th>Invoice Date</th>
                                    <td>{{ $invoice->created_at->format('d-M-Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Patient Name</th>
                                    <td>{{ $invoice->patient->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Doctor Name</th>
                                    <td>{{ $invoice->doctor->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bill Type</th>
                                    <td>{{ ucfirst($invoice->bill_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{ number_format($invoice->sub_total, 2) }}</td>
                                </tr>

                                <tr>
                                    <th>Discount</th>
                                    <td>₹{{ number_format($invoice->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>GST</th>
                                    <td>{{ number_format($invoice->gst, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ number_format($invoice->total, 2) }}</td>
                                </tr>

                                <tr>
                                    <th>Pending Amount</th>
                                    <td>{{ number_format($invoice->pending_amount, 2) }}</td>
                                </tr>

                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        {{ \App\Models\Invoice::getPaymentStatusLabel($invoice->payment_status) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Update Payment</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="payment_date">Date</label>
                                                <input type="datetime-local" name="payment_date" id="payment_date"
                                                    class="form-control"
                                                    value="{{ $invoice->payment_date ?? date('Y-m-d\TH:i') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="payment_mode">Payment Mode</label>
                                                <select name="payment_mode" id="payment_mode" class="form-control">
                                                    <option value="cash"
                                                        {{ $invoice->payment_mode === 'cash' ? 'selected' : '' }}>Cash
                                                    </option>
                                                    <option value="card"
                                                        {{ $invoice->payment_mode === 'card' ? 'selected' : '' }}>Card
                                                    </option>
                                                    <option value="online"
                                                        {{ $invoice->payment_mode === 'online' ? 'selected' : '' }}>Online
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="payment_amount">Amount</label>
                                                <input type="number" name="payment_amount" id="payment_amount"
                                                    class="form-control" step="0.01"
                                                    value="{{ $invoice->payment_amount }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <button type="submit" class="btn btn-primary">Update Payment</button>
                        </form>

                        <!-- Pharmacy Items -->
                        @if ($pharmacyItems->count())
                            <h5>Items</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pharmacyItems as $item)
                                        <tr>
                                            <td>{{ $item->stock->item->name ?? ($item->stock->item->item_code ?? '') }}
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->item_price, 2) }}</td>
                                            <td>{{ number_format($item->add_dis_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($item->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <!-- Download Attachment -->
                        @if ($invoice->attachment)
                            <h5>Attachment</h5>
                            <a href="{{ $invoice->attachment }}" target="_blank" class="btn btn-info">View Attachment</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
