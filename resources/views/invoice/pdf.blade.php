<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 30px;
            background-color: #fff;
            color: #333;
        }

        /* Header section */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo img {
            max-width: 100px;
            height: auto;
        }

        .institute-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 4px;
        }

        .sub-text {
            font-size: 12px;
            color: #4a5568;
            line-height: 1.4;
        }

        /* Address box */
        .address-box {
            margin: 10px 0;
            padding: 5px;
            background: #f8fafc;
            border-left: 4px solid #1a365d;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.6;
        }

        /* Invoice info section */
        .invoice-info {
            width: 100%;
            margin: 20px 0 2px 0 !important;
            padding: 5px;
            background: #f8fafc;
            border-radius: 6px;
            font-size: 10px;
        }

        .invoice-info td {
            vertical-align: top;
            padding: 5px;
        }

        /* Patient details */
        .patient-details {
            margin: 25px 0;
        }

        .patient-details table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
        }

        .patient-details td {
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        /* Summary section */
        .summary-section {
            margin: 25px 0;
        }

        .summary-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-section td {
            padding: 5px;
            border: 1px solid #e2e8f0;
            background: #fff;
        }

        .total-amount {
            background: #d2d2d2;
            color: #fff;
            font-weight: bold;
        }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th,
        .items-table td {
            padding: 5px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }

        .items-table th {
            background: #f3f3f3;
            color: #000;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        /* Footer */
        .footer {
            text-align: center;
            color: #718096;
            font-size: 12px;
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->




    <!-- Invoice Information -->
    <table class="invoice-info" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding: 5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 120px;"><strong>Invoice #:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 120px;"><strong>Issued:</strong></td>
                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding: 5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 120px;"><strong>Patient ID:</strong></td>
                        <td>{{ $invoice->patient->id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 120px;"><strong>Service Date:</strong></td>
                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Patient Details -->
    <div class="patient-details">
        <table style="width:100%; border-collapse: collapse;">
            <colgroup>
                <col style="width:15%">
                <col style="width:35%">
                <col style="width:15%">
                <col style="width:35%">
            </colgroup>
            <tr>
                <td><strong>Patient Name:</strong></td>
                <td>{{ $invoice->patient->name ?? 'N/A' }}</td>
                <td><strong>Gender/Age:</strong></td>
                <td>{{ ucfirst($invoice->patient->gender ?? 'N/A') }} / {{ $invoice->patient->age ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Contact:</strong></td>
                <td>
                    {{ $invoice->patient->phone ?? 'N/A' }}<br>
                    {{ $invoice->patient->email ?? '' }}
                </td>
                <td><strong>Address:</strong></td>
                <td>{{ $invoice->patient->address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Section -->


    <!-- Pharmacy Items (if any) -->
    @if ($pharmacyItems->count())
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pharmacyItems as $item)
                    <tr>
                        <td>{{ $item->stock->item->name ?? $item->stock->item->item_code }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right currency">₹{{ number_format($item->item_price, 2) }}</td>
                        <td class="text-right currency">₹{{ number_format($item->add_dis_amount ?? 0, 2) }}</td>
                        <td class="text-right currency">₹{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="summary-section">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right currency">₹{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td>Discount ({{ number_format($invoice->discount, 2) }}%)</td>
                <td class="text-right currency">
                    -₹{{ number_format(($invoice->sub_total * $invoice->discount) / 100, 2) }}</td>
            </tr>
            <tr>
                <td>GST ({{ number_format($invoice->gst, 2) }}%)</td>
                <td class="text-right currency">₹{{ number_format(($invoice->sub_total * $invoice->gst) / 100, 2) }}
                </td>
            </tr>
            <tr class="total-amount">
                <td><strong>Total Amount Payable</strong></td>
                <td class="text-right currency"><strong> ₹{{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
            @if (isset($invoiceTransactions) && $invoiceTransactions->count())
                @foreach ($invoiceTransactions as $transaction)
                    <tr>
                        <td style="padding-left: 20px;">
                            Payment on {{ \Carbon\Carbon::parse($transaction->payment_date)->format('d M Y') }} via
                            {{ ucfirst($transaction->payment_mode) }}
                        </td>
                        <td class="text-right currency">₹{{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            @if (isset($invoice->pending_amount) && $invoice->pending_amount > 0)
                <tr class="total-amount">
                    <td><strong>Pending Payment</strong></td>
                    <td class="text-right currency"><strong>₹{{ number_format($invoice->pending_amount, 2) }}</strong>
                    </td>
                </tr>
            @endif
        </table>
    </div>



    <!-- Footer -->
    <div class="footer">
        This is a computer-generated invoice. No signature required.<br>
        Thank you for choosing S.I.V.A.S Health &amp; Research Institute
    </div>
</body>

</html>
