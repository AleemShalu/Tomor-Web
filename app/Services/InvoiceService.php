<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceService
{
    public function generateInvoiceNumber()
    {
        // Retrieve the last invoice from the database
        $lastInvoice = Invoice::orderBy('id', 'desc')
            ->lockForUpdate()
            ->setBindings([], 'select')
            ->first();

        // Calculate the next invoice number
        $nextInvoiceNumber = $lastInvoice ? str_pad($lastInvoice->id + 1, 10, '0', STR_PAD_LEFT) : '0000000001';

        // Create and return the invoice number
        return $nextInvoiceNumber;
    }


}
