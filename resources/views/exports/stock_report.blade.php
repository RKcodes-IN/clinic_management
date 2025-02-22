<table>
    <thead>
        <tr>
            <th>#</th>
            <th>id</th>
            <th>Item Name</th>
            <th>Brand</th>
            <th>Item Code</th>
            <th>Expiry Date</th>
            <th>Price</th>

            <th>Bal. Stock</th>
            <th>rack</th>
            <th>correct_exp
            </th>
            <th>correct_price
            </th>
            <th>correct_stock

            </th>
            <th>delete_yn

            </th>

            <th>Remarks</th>s
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->id }}</td>

                <td>{{ $transaction->item->name }}</td>

                @php
                    $brand = \App\Models\Brand::where('id', $transaction->item->brand_id)->first();
                @endphp
                <td>{{ Str::limit($brand->name ?? 'N/A', 5, '') }}</td>

                <td>{{ $transaction->item->item_code }}</td>
                @php
                    $formattedDate = \Carbon\Carbon::parse($transaction->expiry_date)->format('d-M-y');
                    $parts = explode('-', $formattedDate);
                @endphp
                <td>
                    {{ $parts[0] }}-{{ $parts[1] }}-<strong>{{ $parts[2] }}</strong>
                </td>
                <td>{{ $transaction->item_price ?? '' }}</td>


                <td><b>{{ \App\Models\Stock::getTotalStock($transaction->id) }}</b></td>
                <td>{{ $transaction->item->rack ?? '' }}</td>
                <td>{{ $transaction->correct_exp ?? '' }}</td>
                <td>{{ $transaction->correct_price ?? '' }}</td>
                <td>{{ $transaction->correct_stock ?? '' }}</td>
                <td>{{ $transaction->delete_yn ?? '' }}</td>

                <td>&nbsp;</td>
            </tr>
        @endforeach
    </tbody>
</table>
