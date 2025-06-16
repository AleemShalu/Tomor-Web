<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item_translation()
    {
        return $this->belongsTo(ItemTranslation::class, 'item_transalation_id');
    }

}
