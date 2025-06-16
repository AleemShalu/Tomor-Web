<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Invoice styles */
        .invoice {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .invoice h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice .header .company-info {
            font-weight: bold;
        }

        .invoice .header .company-info span {
            display: block;
        }

        .invoice .header .invoice-info {
            text-align: right;
        }

        .invoice .details {
            margin-bottom: 20px;
        }

        .invoice .details .client-info {
            margin-bottom: 10px;
        }

        .invoice .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice .details table th,
        .invoice .details table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .invoice .details table th {
            background-color: #f5f5f5;
        }

        .invoice .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }

        .invoice .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        /* Additional styles */
        .invoice .details .client-info span {
            display: block;
        }

        .invoice .details table td:nth-child(2),
        .invoice .details table td:nth-child(3),
        .invoice .details table td:nth-child(4) {
            text-align: right;
        }

        .invoice .details table td:nth-child(2) {
            width: 80px;
        }

        .invoice .details table td:nth-child(3),
        .invoice .details table td:nth-child(4) {
            min-width: 80px;
        }

        .invoice .details table td:last-child {
            font-weight: bold;
        }

        .invoice .total span {
            font-size: 20px;
            color: #333;
        }

        .invoice .platform {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice .platform img {
            max-width: 200px;
        }
    </style>
</head>
<body>
<div class="invoice">
    <h1>Invoice</h1>

{{--    <div class="platform">--}}
{{--        <img src="{{asset('images/7ader.jpg')}}" alt="Logo">--}}
{{--    </div>--}}
    <div class="header">
        <div class="company-info">
            <span>Your Company Name</span>
            <span>123 Main Street</span>
            <span>City, State, ZIP</span>
            <span>Phone: (123) 456-7890</span>
        </div>
        <div class="invoice-info">
            <span>Invoice #: INV-001</span>
            <span>Date: July 15, 2023</span>
        </div>
    </div>

    <div class="details">
        <div class="client-info">
            <span>Client Name: John Doe</span>
            <span>Address: 456 Oak Avenue</span>
            <span>City, State, ZIP</span>
        </div>
        <table>
            <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Item 1</td>
                <td>2</td>
                <td>$10.00</td>
                <td>$20.00</td>
            </tr>
            <tr>
                <td>Item 2</td>
                <td>1</td>
                <td>$15.00</td>
                <td>$15.00</td>
            </tr>
            <tr>
                <td>Item 3</td>
                <td>3</td>
                <td>$5.00</td>
                <td>$15.00</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="total">
        <span>Total: $50.00</span>
    </div>
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</div>
</body>
</html>
