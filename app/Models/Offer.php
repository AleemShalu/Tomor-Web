<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Base
{
    use HasFactory;

    protected $fillable = [
        'offer_name',
        'offer_description',
        'discount_percentage',
        'status',
        'start_date',
        'end_date',
        'store_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function productOffers()
    {
        return $this->hasMany(ProductOffer::class, 'offer_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_offers', 'offer_id', 'product_id');
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offers', 'product_id', 'offer_id');
    }


}