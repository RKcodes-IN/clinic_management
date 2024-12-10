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

        th, td {
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
                <th>Invoice ID</th>
                <th>PO ID</th>
                <th>Date</th>
                <th>Transaction Type</th>
                <th>Quantity</th>
                <th>Item Price</th>
                <th>Total Price</th>
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
                    <td>{{ $transaction->invoiceDetail->invoice->invoice_number ?? '' }}</td>
                    <td>{{ $transaction->purchase_order_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-M-Y') }}</td>
                    <td>{{ $transaction->status == 1 ? 'Incoming' : 'Outgoing' }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->item_price }}</td>
                    <td>{{ $transaction->total_price }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->stock->expiry_date)->format('d-M-Y') }}</td>
                    <td>{{ \App\Models\StockTransaction::getBalanceStockByDate($transaction->stock_id, $transaction->id) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
