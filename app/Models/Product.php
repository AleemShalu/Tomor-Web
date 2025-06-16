<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Base
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        // 'status' => 'boolean',
    ];

    protected $appends = ['has_active_offer'];

    // public function item_type()
    // {
    //     return $this->belongsTo(ItemType::class, 'item_type_id');
    // }

    public function product_brand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // public function vat_code()
    // {
    //     return $this->belongsTo(VatCode::class, 'vat_code_id');
    // }

    // public function branches()
    // {
    //     return $this->belongsToMany(StoreBranch::class, 'product_stock_in_branches', 'product_id', 'store_branch_id')->withPivot('stock');
    // }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    // public function packings()
    // {
    //     return $this->hasMany(ItemPacking::class);
    // }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // public function invoice_items()
    // {
    //     return $this->hasMany(InvoiceItem::class);
    // }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'product_category_id');
    // }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_for_products', 'product_id', 'coupon_id')->withTimestamps();
    }

    public function product_offer()
    {
        return $this->hasMany(ProductOffer::class, 'product_id');
    }

    public function has_active_offer()
    {
        return $this->product_offer()
            ->whereHas('offer', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->exists();
    }

    public function getHasActiveOfferAttribute()
    {
        return $this->has_active_offer();
    }

    public function getDiscountedPriceAttribute()
    {
        if (!$this->has_active_offer) {
            return $this->unit_price;
        }

        $product_offer = $this->product_offer->last();

        if (!$product_offer || !$product_offer->offer) {
            return $this->unit_price;
        }

        return $this->unit_price * (1 - $product_offer->offer->discount_percentage / 100);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }

    public function scopeFilterByRole($query)
    {
        $user = auth()->user();

        if ($user && $user->hasRole('customer')) {
            return $query->where('status', 1);
        } else {
            return $query;
        }
    }

}
