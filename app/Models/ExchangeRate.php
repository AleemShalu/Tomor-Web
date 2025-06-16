<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Base
{
    use HasFactory;

    // public function getRateAttribute($value)
    // {
    //     return number_format($value, 2);
    // }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'target_currency');
    }
}
