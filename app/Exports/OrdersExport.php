<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order Number',
            'Customer Name',
            'Order Date',
            'Status',
            'Customer Dial Code',
            'Customer Contact No',
            'Customer Email',
            'Customer Vehicle Description',
            'Customer Vehicle Color',
            'Customer Vehicle Plate',
            'Items Count',
            'Items Quantity',
            'Exchange Rate',
            'Conversion Time',
            'Order Currency Code',
            'Base Currency Code',
            'Grand Total',
            'Base Grand Total',
            'Sub Total',
            'Base Sub Total',
            'Service Total',
            'Base Service Total',
            'Discount Total',
            'Base Discount Total',
            'Tax Total',
            'Base Tax Total',
            'Taxable Total',
            'Base Taxable Total',
            'Checkout Method',
            'Coupon Code',
            'Is Gift',
            'Store ID',
            'Store Branch ID',
            'Employee ID',
            'Created At',
            'Updated At',
        ];
    }
}
