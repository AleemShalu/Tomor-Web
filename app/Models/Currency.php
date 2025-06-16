<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Base
{
    use HasFactory;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function exchange_rate()
    {
        return $this->hasOne(ExchangeRate::class, 'target_currency');
    }
}
