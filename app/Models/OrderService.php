<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderService extends Base
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_definition_id',
        'service_currency_code',
        'price',
    ];

    /**
     * Get the order associated with the OrderService
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the service definition associated with the OrderService
     */
    public function serviceDefinition()
    {
        return $this->belongsTo(ServiceDefinition::class);
    }
}