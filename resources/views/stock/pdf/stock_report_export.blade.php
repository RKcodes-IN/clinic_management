<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Transactions PDF</title>
    <style>
        /* Basic styling for the PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .filter-info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>Stock Transactions</h2>

    <div class="filter-info">
        <strong>From Date:</strong> {{ $fromDate ?? 'N/A' }}<br>
        <strong>To Date:</strong> {{ $toDate ?? 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Expiry Date</th>
                <th>Balance Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->item->name }}</td>
                    <td>{{ $transaction->item->item_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->expiry_date)->format('d-M-Y') }}</td>
                    <td>{{ \App\Models\Stock::getTotalStock($transaction->id) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
