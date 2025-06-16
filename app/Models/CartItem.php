<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
