<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Coupon extends Base
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_percentage' => 'float',
        'discount_amount' => 'float',
        'min_amount' => 'float',
    ];

    public function coupon_type()
    {
        return $this->belongsTo(CouponType::class);
    }

    public function discount_type()
    {
        return $this->belongsTo(DiscountType::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'coupons_for_stores', 'coupon_id', 'store_id')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupons_for_users', 'coupon_id', 'user_id')->withPivot('usage_count')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupons_for_products', 'coupon_id', 'product_id')->withTimestamps();
    }

    public function coupon_redemption_log()
    {
        return $this->hasMany(CouponRedemptionLog::class);
    }

    // public function redeemCouponForUser(User $user, Order $order)
    // {
    //     if (!$this->canBeRedeemed($user)) {
    //         return false;
    //     }

    //     return true;
    // }

    // public function canBeAppliedToOrder(User $user, $orderAmount)
    // {
    //     return $this->canBeRedeemed($user) && $orderAmount >= $this->min_amount;
    // }

    // public function canBeRedeemed(User $user)
    // {
    //     return $this->isActive() && $this->canBeRedeemedGlobally() && $this->canBeRedeemedByUser($user);
    // }

    protected function canBeRedeemedGlobally()
    {
        if ($this->max_uses === null) {
            return true; // No coupon usage limit
        }

        return $this->usage_count < $this->max_uses;  // to redeem a coupon, global usage_count must be less than max_uses
    }

    protected function canBeRedeemedByUser(User $user)
    {
        if ($this->per_user_max_uses === null) {
            return true; // No per-user limit, user can always redeem
        }

        $userPivot = $this->users->find($user->id)->pivot;
        // to redeem a coupon, user's usage_count must be less than coupon's per_user_max_uses
        return !$userPivot || $userPivot->usage_count < $this->per_user_max_uses;
    }

    public function isCouponActive()
    {
        return $this->enabled && $this->start_date <= now() && $this->end_date >= now();
    }

    public function scopeActive($query)
    {
        return $query->where('enabled', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public static function cachedActiveCoupons()
    {
        return Cache::remember('active_coupons', now()->addMinutes(30), function () {
            return self::with('users')->active()->get(['id', 'code', 'discount_amount', 'discount_type']);
        });
    }

    public function incrementUserUsageCount(User $user)
    {
        $pivotData = $user->coupons()->where('coupon_id', $this->id)->first()->pivot;
        $pivotData->usage_count++;  // increment user max_uses
        $user->coupons()->updateExistingPivot($this->id, $pivotData);
    }

    public function incrementUsageCount()
    {
        $this->usage_count++; // increment coupon global max_uses
        $this->save();
    }
}
