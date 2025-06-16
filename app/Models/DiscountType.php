<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountType extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
