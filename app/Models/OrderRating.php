<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderRating extends Base
{
    use HasFactory;

    protected $guarded = [];


    public function order_rating_type()
    {
        return $this->belongsTo(OrderRatingType::class, 'order_rating_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function averageRating()
    {
        return $this->hasMany(OrderRating::class, 'store_id')->avg('rating');
    }


}
