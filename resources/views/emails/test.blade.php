<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Alert</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Stock Alert Notification</h2>
    <p>The following items are below their minimum stock levels:</p>

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Alert Quantity</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockAlerts as $alert)
            <tr>
                <td>{{ $alert['name'] }}</td>
                <td>{{ $alert['item_code'] }}</td>
                <td>{{ $alert['alert_quantity'] }}</td>
                <td style="color: red;">{{ $alert['available_stock'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Please take necessary actions to restock these items.</p>
</body>
</html>
