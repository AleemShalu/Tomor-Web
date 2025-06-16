<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderRatingType extends Base
{
    use HasFactory;


    public function order_ratings()
    {
        return $this->hasMany(OrderRating::class, 'order_rating_id');
    }

}
