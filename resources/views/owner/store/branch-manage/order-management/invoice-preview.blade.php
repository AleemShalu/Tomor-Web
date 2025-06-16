<!DOCTYPE html>
<html>
<head>
    <title>Preview Invoice</title>
    <style>
        /* Add your CSS styles for the invoice here */
        /* For example, you can style the layout, fonts, colors, etc. */
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .invoice-total {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-center text-3xl font-bold mb-8">Preview Invoice</h1>
    {{-- <p>Branch ID: {{ $branchId }}</p> --}}
    <p>Invoice ID: {{ $invoiceId }}</p>

    <!-- Render the invoice details -->
    <div class="invoice-details mt-8">
        <h2 class="text-2xl font-bold mb-4">Invoice Details</h2>
        <p>Invoice Number: {{ $invoice->invoice_number }}</p>
        <p>Status: {{ $invoice->status }}</p>
        <p>Invoice Date: {{ $invoice->invoice_date }}</p>
        <p>Supply Date: {{ $invoice->supply_date }}</p>
        <p>Grand Total: {{ $invoice->grand_total }}</p>
        <!-- Include other relevant invoice details here -->
    </div>
</div>
</body>
</html>
