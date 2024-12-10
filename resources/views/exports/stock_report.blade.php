<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Item Code</th>
            <th>Transaction Type</th>
            <th>Balance Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->item->name }}</td>
                <td>{{ $transaction->item->item_code }}</td>
                <td>{{ $transaction->expiry_date ? \Carbon\Carbon::parse($transaction->expiry_date)->format('d-M-Y') : '-' }}
                </td>
                <td>{{ \App\Models\Stock::getTotalStock($transaction->id) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
