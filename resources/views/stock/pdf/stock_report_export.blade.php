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
            font-size: 10px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            /* Reduced padding for compactness */
            text-align: center;
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
        }

        th:nth-child(1) {
            width: 5%;
            /* Adjust column width for compact table */
        }

        th:nth-child(2) {
            width: 20%;
        }

        th:nth-child(3) {
            width: 10%;
        }

        th:nth-child(4) {
            width: 10%;
        }

        th:nth-child(5) {
            width: 10%;
        }

        th:nth-child(6) {
            width: 8%;
        }

        th:nth-child(7) {
            width: 8%;
        }

        th:nth-child(8) {
            width: 8%;
        }

        th:nth-child(9) {
            width: 19%;
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
    <h2>Stocks</h2>

    <div class="filter-info">
        <strong>From Date:</strong> {{ $fromDate ?? 'N/A' }}<br>
        <strong>To Date:</strong> {{ $toDate ?? 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Brand</th>
                <th>Item Code</th>
                <th>Expiry Date</th>
                <th>Price</th>

                <th>Bal. Stock</th>
                <th>Rack</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
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

                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
