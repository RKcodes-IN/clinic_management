<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        /* Header Styles */
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border: none;
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo-cell {
            width: 10%;
            padding-right: 20px;
        }

        .logo-cell img {
            max-height: 50px;
            /* Limit the maximum height */
            max-width: 120px;
            /* Optionally limit the maximum width */
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .header-text-cell {
            width: 90%;
            text-align: left;
            padding-left: 15px;
            border-left: 2px solid #e0e0e0;
        }

        .institute-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sub-text {
            font-size: 11px;
            line-height: 1.5;
            color: #34495e;
        }

        /* Enhanced Address Box */
        .address {
            margin: 20px 0;
            padding: 12px 15px;
            font-size: 11px;
            background-color: #f8f9fa;
            border-left: 4px solid #2c3e50;
            border-radius: 0 4px 4px 0;
            line-height: 1.6;
        }

        /* Enhanced Bill To Section */
        .bill-to {
            margin: 20px 0;
            padding: 15px;
            font-size: 12px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Tables */
        .compact-summary {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .compact-summary td {
            padding: 12px;
            border: 1px solid #e0e0e0;
            font-size: 12px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .content-table th,
        .content-table td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: left;
        }

        .table-heading {
            background-color: #2c3e50;
            color: white;
            font-weight: normal;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Invoice Number Styling */
        .invoice-number {
            font-size: 14px;
            color: #2c3e50;
            margin: 15px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 4px;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }

        /* Summary Section */
        .summary-section {
            margin-top: 20px;
        }

        .summary-section h5 {
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Row Colors */
        .content-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Total Amount */
        .total-amount {
            font-weight: bold;
            color: #2c3e50;
            background-color: #f8f9fa;
        }

        /* Currency and Numbers */
        .currency {
            font-family: monospace;
        }
    </style>
</head>

<body>
    <!-- Header using table layout -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="https://ik.imagekit.io/phbranchi/logo-ct_dMECUkXSB.png?updatedAt=1734284741500"
                    alt="Logo" />
            </td>
            <td class="header-text-cell">
                <span class="institute-name">S.I.V.A.S Health & Research Institute</span>
                <span class="sub-text">
                    Center for Health by Integration of Modern Medicine, Ayurveda & Yoga<br />
                    and<br />
                    Center for Eye Diseases
                </span>
            </td>
        </tr>
    </table>

    <!-- Address -->
    <div class="address">
        <strong>Street Address:</strong> H.No. 10-2-172, St. John's Road,<br />
        Opposite Keyes High School,<br />
        Secunderabad Telangana 500025<br />
        <strong>Phone:</strong> +91 9848157629
    </div>

    <!-- Invoice Number and Date -->
    <div class="invoice-number">
        <strong>Invoice:</strong> #{{ $invoice->invoice_number }}
        <span style="float: right;"><strong>Date:</strong> {{ $invoice->created_at->format('d-M-Y H:i') }}</span>
    </div>

    <!-- Bill To -->
    <div class="bill-to">
        <strong>Bill To:</strong> {{ $invoice->patient->name ?? 'N/A' }}<br />
        <strong>Age:</strong> {{ $invoice->patient->age ?? 'N/A' }} <strong>Gender:</strong>
        {{ ucfirst($invoice->patient->gender ?? 'N/A') }}<br />
        <strong>Address:</strong> {{ $invoice->patient->address ?? 'N/A' }}<br />
        <strong>Phone:</strong> {{ $invoice->patient->phone ?? 'N/A' }}<br />
        <strong>Email:</strong> {{ $invoice->patient->email ?? 'N/A' }}
    </div>

    <!-- Compact Invoice Summary -->
    <div class="summary-section">
        <h5>Invoice Summary</h5>
        <table class="compact-summary">
            <tr>
                <td><strong>Invoice Date:</strong> {{ $invoice->created_at->format('d-M-Y H:i') }}</td>
                <td><strong>Patient Name:</strong> {{ $invoice->patient->name ?? 'N/A' }}</td>
                <td><strong>Doctor Name:</strong> {{ $invoice->doctor->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Bill Type:</strong> {{ ucfirst($invoice->bill_type) }}</td>
                <td><strong>Subtotal:</strong> <span
                        class="currency">₹{{ number_format($invoice->sub_total, 2) }}</span></td>
                <td><strong>Discount:</strong> {{ number_format($invoice->discount, 2) }}%</td>
            </tr>
            <tr>
                <td><strong>GST:</strong> {{ number_format($invoice->gst, 2) }}%</td>
                <td colspan="2" class="total-amount"><strong>Total Amount:</strong> <span
                        class="currency">₹{{ number_format($invoice->total, 2) }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Pharmacy Items -->
    @if ($pharmacyItems->count())
        <div class="summary-section">
            <h5>Items</h5>
            <table class="content-table">
                <thead>
                    <tr>
                        <th class="table-heading">Item Name</th>
                        <th class="table-heading">Quantity</th>
                        <th class="table-heading">Price</th>
                        <th class="table-heading">Discount</th>
                        <th class="table-heading">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacyItems as $item)
                        <tr>
                            <td>{{ $item->stock->item->name ?? $item->stock->item->item_code }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="currency">₹{{ number_format($item->item_price, 2) }}</td>
                            <td class="currency">₹{{ number_format($item->add_dis_amount ?? 0, 2) }}</td>
                            <td class="currency">₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>

</html>
