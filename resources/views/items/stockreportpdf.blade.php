<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; margin: 0; padding: 0;">

    <h2 style="text-align: center; margin-bottom: 20px;">Stock Report</h2>

    <!-- Item Details Section -->
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px;">Item Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px;">
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Item Name</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->name }}</td>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Item Code</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->item_code }}</td>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Source Company</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->company->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Alert Quantity</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->alert_quantity }}</td>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Category</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->category->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Total Balance</th>
                <td style="border: 1px solid #000; padding: 6px;">{{ $item->getTotalStockByItem($item->id) }}</td>
            </tr>
        </table>
    </div>

    <!-- Stock Details Section -->
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px;">Stock Details (by Expiry Date)</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Expiry Date</th>
                    <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Balance Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stock as $stk)
                    <tr>
                        <td style="border: 1px solid #000; padding: 6px;">{{ \Carbon\Carbon::parse($stk->expiry_date)->format('d-m-Y') }}</td>
                        <td style="border: 1px solid #000; padding: 6px;">{{ $stk->getTotalStock($stk->id) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Stock Transactions Section -->
    <div>
        <h3 style="margin-bottom: 10px;">Stock Transactions</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Date</th>
                    <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Quantity</th>
                    <th style="border: 1px solid #000; padding: 6px; text-align: left; background-color: #f4f4f4;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockTransaction as $transaction)
                    <tr>
                        <td style="border: 1px solid #000; padding: 6px;">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                        <td style="border: 1px solid #000; padding: 6px;">{{ $transaction->quantity }}</td>
                        <td style="border: 1px solid #000; padding: 6px;">{{ $transaction->status == 1 ? 'Incoming' : 'Outgoing' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
