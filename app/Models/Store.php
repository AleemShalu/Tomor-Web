<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Base
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'business_type_id',
        'commercial_name_en',
        'commercial_name_ar',
        'short_name_en',
        'short_name_ar',
        'description',
        'email',
        'country_id',
        'dial_code',
        'contact_no',
        'secondary_dial_code',
        'secondary_contact_no',
        'tax_id_number',
        'commercial_registration_no',
        'commercial_registration_expiry',
        'municipal_license_no',
        'api_url',
        'api_admin_url',
        'website',
        'owner_id',
        'tax_id_attachment',
        'commercial_registration_attachment',
        'store_header',
        'capacity'
    ];

    protected $casts = [
        // 'rating' => 'float',
        // 'rating_count' => 'float',
    ];

    public function columnsExport()
    {
        return
            [
                'id',
                'business_type_name',
                'commercial_name_en',
                'commercial_name_ar',
                'short_name_en',
                'short_name_ar',
                'description_ar',
                'description_en',
                'email',
                'phone_number',
                'secondary_dial_code',
                'secondary_contact_no',
                'tax_id_number',
                'commercial_registration_no',
                'commercial_registration_expiry',
                'municipal_license_no',
            ];
    }

    public function business_type(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }


    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(StoreBranch::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function bank_accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function work_statuses(): HasMany
    {
        return $this->hasMany(BranchWorkStatus::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupons_for_stores', 'store_id', 'coupon_id')->withTimestamps();
    }

    public function order_ratings(): HasMany
    {
        return $this->hasMany(OrderRating::class, 'store_id');
    }

    public function getRatingAvgAttribute()
    {
        $ratingAvg = $this->order_ratings->avg('rating');
        return $ratingAvg; // Format as a float with one decimal place
    }

    public function getRatingsCountAttribute()
    {
        $ratingsCount = $this->order_ratings->count();
        return $ratingsCount; // Format as a float with one decimal place
    }

    public function branch_visitors()
    {
        return $this->hasMany(BranchVisitor::class, 'store_id');
    }

    public function order_service()
    {
        return $this->hasMany(OrderService::class, 'store_id');
    }

    public function store_name()
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->commercial_name_ar;
        } else {
            return $this->commercial_name_en;
        }
    }

    public function getBusinessTypeNameAttribute()
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->business_type->name_ar;
        } else {
            return $this->business_type->name_en;
        }
    }

    public function getPhoneNumberAttribute()
    {
        return $this->dial_code . $this->contact_no;
    }
}
