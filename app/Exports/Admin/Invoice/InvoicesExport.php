<?php

namespace App\Exports\Admin\Invoice;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;

class InvoicesExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get only the columns needed for export
        return Invoice::select(
            'id',
            'invoice_number',
            'status',
            'customer_name',
            'store_name_ar',
            'store_invoice_number',
            'store_branch_name_ar',
            'invoice_date',
            'order_id',
            'customer_email',
            'total_vat',
            'invoice_discount_percentage',
            'invoice_discount_amount',
            'total_discount',
            'total_taxtable_amount',
            'gross_total_including_vat',
            'conversion_time',
            'total_vat_in_sar'
        // Add more columns as needed
        )->get();
    }

    /**
     * Specify the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        // Customize the headings based on your selected columns
        return [
            '#',
            'Invoice Number',
            'Status',
            'Customer Name',
            'Store Name',
            'Store Invoice Number',
            'Store Branch Name',
            'Invoice Date',
            'Order ID',
            'Customer Email',
            'Total VAT',
            'Invoice Discount Percentage',
            'Invoice Discount Amount',
            'Total Discount',
            'Total Taxtable Amount',
            'Gross Total Including VAT',
            'Conversion Time',
            'Total VAT in SAR',
            // Add more headings as needed
        ];
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet|\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // Set a fixed width for all columns
        $columnWidth = 25; // Adjust the width as needed

        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'], // Text color (white)
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD'], // Background color (blue)
                ],
            ],

            // Set a fixed width for all columns
            'A:Z' => [
                'column_width' => $columnWidth,
            ],
        ];
    }
}