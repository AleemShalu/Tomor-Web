<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your custom styles for the PDF report here */
        /* Example styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td, table th {
            padding: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<h1>Order Report</h1>


<table>
    <tr>
        <th>ID</th>
        <th>Order Number</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Customer Email</th>
        <th>Items Count</th>
        <!-- Add more table headers for additional order details -->
        <!-- Example: <th>Order Currency Code</th> -->
    </tr>
    @foreach ($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->user->email }}</td>
            <td>{{ $order->items_count }}</td>
            <!-- Add more table cells for additional order details -->
        </tr>
    @endforeach
</table>

<!-- Add more sections or information as needed for the PDF report -->
</body>
</html>
