<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .header {
            display: flex;
            align-items: center;
            /* Vertically aligns items */
            margin-bottom: 20px;
        }

        .header-logo {
            width: 15%;
            /* Adjust width as needed */
            text-align: left;
        }

        .header-logo img {
            height: 50px;
            /* Ensure the logo is of consistent height */
        }

        .header-text {
            width: 85%;
            text-align: left;
            /* Aligns text to the left */
            padding-left: 10px;
            /* Adds spacing between the logo and text */
        }

        .header-text strong {
            font-size: 16px;
            display: block;
        }

        .address {
            margin-top: 20px;
            font-size: 11px;
            text-align: left;
        }

        .bill-to {
            margin-bottom: 20px;
            font-size: 12px;
            text-align: left;
        }

        .table-heading {
            background-color: #f2f2f2;
        }

        .compact-summary {
            width: 100%;
            border: 1px solid black;
            margin-bottom: 20px;
        }

        .compact-summary td {
            padding: 5px;
            border: 1px solid black;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <!-- Logo -->
        <div class="header-logo">
            <img src="https://ik.imagekit.io/phbranchi/logo-ct_dMECUkXSB.png?updatedAt=1734284741500" alt="Logo" />
        </div>

        <!-- Header Text -->
        <div class="header-text">
            <strong>S.I.V.A.S Health & Research Institute</strong>
            Center for Health by Integration of Modern Medicine, Ayurveda & Yoga<br />
            and<br />
            Center for Eye Diseases
        </div>
    </div>

    <!-- Address -->
    <div class="address">
        <strong>Street Address:</strong> H.No. 10-2-172, St. John's Road,<br />
        Opposite Keyes High School,<br />
        Secunderabad Telangana 500025<br />
        <strong>Phone:</strong> +91 9848157629
    </div>

    <!-- Bill To -->
    <div class="bill-to">
        <strong>Bill To:</strong> {{ $invoice->patient->name ?? 'N/A' }}<br />
        Age: {{ $invoice->patient->age ?? 'N/A' }} Gender: {{ ucfirst($invoice->patient->gender ?? 'N/A') }}<br />
        Address: {{ $invoice->patient->address ?? 'N/A' }}<br />
        Phone: {{ $invoice->patient->phone ?? 'N/A' }}<br />
        Email: {{ $invoice->patient->email ?? 'N/A' }}
    </div>

    <!-- Invoice Number and Date -->
    <h4>Invoice: #{{ $invoice->invoice_number }}</h4>
    <h5>Invoice Date: {{ $invoice->created_at->format('d-M-Y H:i') }}</h5>

    <!-- Compact Invoice Summary -->
    <h5>Invoice Summary</h5>
    <table class="compact-summary">
        <tr>
            <td><strong>Invoice Date:</strong> {{ $invoice->created_at->format('d-M-Y H:i') }}</td>
            <td><strong>Patient Name:</strong> {{ $invoice->patient->name ?? 'N/A' }}</td>
            <td><strong>Doctor Name:</strong> {{ $invoice->doctor->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Bill Type:</strong> {{ ucfirst($invoice->bill_type) }}</td>
            <td><strong>Subtotal:</strong> {{ number_format($invoice->sub_total, 2) }}</td>
            <td><strong>Discount:</strong> {{ number_format($invoice->discount, 2) }}%</td>
        </tr>
        <tr>
            <td><strong>GST:</strong> {{ number_format($invoice->gst, 2) }}%</td>
            <td><strong>Total:</strong> {{ number_format($invoice->total, 2) }}</td>
            <td>&nbsp;</td> <!-- Empty cell if not enough data -->
        </tr>
    </table>

    <!-- Pharmacy Items -->
    @if ($pharmacyItems->count())
        <h5>Items</h5>
        <table>
            <thead>
                <tr>
                    <th class="table-heading">Item Name</th>
                    <th class="table-heading">Quantity</th>
                    <th class="table-heading">Price</th>
                    <th class="table-heading">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pharmacyItems as $item)
                    <tr>
                        <td>{{ $item->stock->item->name ?? $item->stock->item->item_code }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->item_price, 2) }}</td>
                        <td>{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>

</html>
