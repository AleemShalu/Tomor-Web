<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTranslation extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function invoice_item()
    // {
    //     return $this->hasMany(InvoiceItem::class, 'item_transalation_id');
    // }

    // public function images()
    // {
    //     return $this->hasMany(ItemImage::class, 'item_id', 'item_id');
    // }
}
