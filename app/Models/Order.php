<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Base
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_number' => 'integer',
        'order_date' => 'datetime',
        'exchange_rate' => 'float',
        'conversion_time' => 'datetime',
        'grand_total' => 'float',
        'base_grand_total' => 'float',
        'sub_total' => 'float',
        'base_sub_total' => 'float',
        'service_total' => 'float',
        'service_total_tax_amount' => 'float',
        'service_total_including_tax' => 'float',
        'base_service_total' => 'float',
        'discount_total' => 'float',
        'base_discount_total' => 'float',
        'tax_total' => 'float',
        'base_tax_total' => 'float',
        'taxable_total' => 'float',
        'base_taxable_total' => 'float',
        'is_guest' => 'boolean',
        'is_gift' => 'boolean',
        'is_paid' => 'boolean',
        'refund_request' => 'boolean', 
        'refund_status' => 'string', 
        'refund_reason' => 'string',
        'is_payment_captured' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branch_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customer_vehicle()
    {
        return $this->belongsTo(CustomerVehicle::class, 'customer_vehicle_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }

    public function order_ratings()
    {
        return $this->hasMany(OrderRating::class, 'order_id');
    }

    public function bank_card()
    {
        return $this->belongsTo(BankCard::class, 'bank_card_id');
    }
}
