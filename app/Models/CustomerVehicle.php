<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerVehicle extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
