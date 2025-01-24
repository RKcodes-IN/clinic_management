<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order #{{ $purchaseOrder->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .header {
            margin-bottom: 20px;
        }

        .company-details {
            margin-top: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .items-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header">
        <table style="width: 100%; border: none !important; margin-bottom: 20px;">
            <tr>
                <!-- Logo -->
                <td style="width: 30%; text-align: left; vertical-align: middle;">
                    <img src="https://indiaseva.net/assets/img/logo-ct.png" alt="S.I.V.A.S Logo"
                        style="max-width: 100px; height: auto;">
                </td>
                <!-- Header Text -->
                <td style="width: 70%; text-align: center; vertical-align: middle;">
                    <h3 style="margin: 0; font-size: 16px;">S.I.V.A.S Health & Research Institute</h3>
                    <p style="margin: 5px 0;">Center for Health by Integration of Modern Medicine, Ayurveda & Yoga</p>
                    <p style="margin: 5px 0;">and</p>
                    <p style="margin: 5px 0;">Center for Eye Diseases</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Purchase Order Information -->
    <div class="header">
        <h2 style="text-align: center;">Purchase Order</h2>
        <p style="text-align: center;">Order #: {{ $purchaseOrder->id }}</p>
        <p style="text-align: center;">Date: {{ $purchaseOrder->created_at->format('Y-m-d') }}</p>
    </div>

    <!-- Company Details -->
    <div class="company-details">
        <h4>Company Details</h4>
        <p><strong>Name:</strong> {{ $company->name }}</p>
        <p><strong>Address:</strong> {{ $company->address }}</p>
        <p><strong>Contact:</strong> {{ $company->contact }}</p>
    </div>

    <!-- Items Table -->
    <div>
        <h4>Items</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>UOM</th> <!-- UOM Column -->

                    <th>Quantity</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->purchaseOrderItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->item->item_code ?? '' }}</td>
                        <td>{{ $item->item->poitem_name ?? '' }}</td>
                        <td>{{ $item->item->pouom->name ?? '' }}</td> <!-- Display UOM Name -->
                        <td>{{ $item->quantity ?? '' }}</td> <!-- Display UOM Name -->
                        <td>{{ $item->quantity *($item->unit_conversion_ratio ?: 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
