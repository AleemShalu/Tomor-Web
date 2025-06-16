<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Base
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     *
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'datetime',
        'supply_date' => 'datetime',
        'additional_ids' => 'array',
    ];

    public function invoice_type()
    {
        return $this->belongsTo(InvoiceType::class, 'invoice_type_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
