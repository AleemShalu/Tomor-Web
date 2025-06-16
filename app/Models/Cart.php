<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Base
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_guest' => 'boolean',
        'is_gift' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }
}
