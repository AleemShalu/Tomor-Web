<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponRedemptionLog extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
