<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceDefinition extends Base
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'service_currency_code',
        'price',
        'rate',
    ];

    public function get_price_attribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function get_rate_attribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
}
