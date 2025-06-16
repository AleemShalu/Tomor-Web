<!DOCTYPE html>
<html>
<head>
    <title>Order Report</title>
    <style>
        /* Add your custom CSS styling for the PDF here */
        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .logo {
            text-align: center;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="logo">
{{--    <img src="{{asset('')}}" alt="Company Logo" width="200">--}}
</div>

<div class="company-info">
    <p>Company Name: Your Company Name</p>
    <p>Address: Your Company Address</p>
    <p>Contact: Your Company Contact Info</p>
    <!-- Add more company information as needed -->
</div>

<h1>Order Report</h1>
<table>
    <thead>
    <tr>
        <th>Order Number</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Customer Name</th>
        <th>Contact No</th>
        <!-- Add other table headers as needed -->
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->customer_contact_no }}</td>
            <!-- Add other table data as needed -->
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
