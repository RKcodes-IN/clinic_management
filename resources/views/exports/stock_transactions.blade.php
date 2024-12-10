<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Item Code</th>
            <th>Invoice ID</th>
            <th>PO ID</th>
            <th>Date</th>
            <th>Transaction Type</th>
            <th>Quantity</th>
            <th>Expiry Date</th>
            <th>Balance Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->item->name }}</td>
                <td>{{ $transaction->item->item_code }}</td>
                <td>{{ $transaction->invoice_id }}</td>
                <td>{{ $transaction->purchase_order_id }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-M-Y') }}</td>
                <td>{{ $transaction->status == 1 ? 'Incoming' : 'Outgoing' }}</td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->stock->expiry_date ? \Carbon\Carbon::parse($transaction->stock->expiry_date)->format('d-M-Y') : '-' }}</td>
                <td>{{ \App\Models\StockTransaction::getBalanceStockByDate($transaction->stock_id, $transaction->id) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
