<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceType extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoice_type_id');
    }
}
