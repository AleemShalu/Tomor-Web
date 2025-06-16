<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class StoreBranch extends Base
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'store_id',
        'name_ar',
        'name_en',
        'commercial_registration_no',
        'commercial_registration_expiry',
        'email',
        'dial_code',
        'contact_no',
        'city_id',
        'branch_serial_number',
        'bank_account_id',
        'commercial_registration_attachment',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function location()
    {
        return $this->hasOne(BranchLocation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'branch_employees', 'store_branch_id', 'employee_id')
            ->withPivot('role_id')->withTimestamps();;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'branch_employees', 'store_branch_id', 'role_id')
            ->withPivot('employee_id')->withTimestamps();;
    }

    public function work_statuses()
    {
        return $this->hasMany(BranchWorkStatus::class);
    }

    public function favoured_by_customers()
    {
        return $this->belongsToMany(User::class, 'customer_favorite_branches', 'store_branch_id', 'customer_id')->withTimestamps();
    }

    public function branch_visitors()
    {
        return $this->hasMany(BranchVisitor::class, 'store_branch_id');
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_stock_in_branches', 'store_branch_id', 'product_id')->withPivot('stock');
    // }

    /**
     * Get the active work status for the store branch.
     */
    public function activeWorkStatus()
    {
        return $this->hasOne(BranchWorkStatus::class)->where('status', 'active');
    }

    /**
     * Get a branch unique unique number.
     */
    public static function generateUniqueSerialNumber()
    {
        $serialNumber = mt_rand(100000, 999999);

        // If the generated serial number already exists, try a new one until you get a unique one.
        while (self::where('branch_serial_number', $serialNumber)->exists()) {
            $serialNumber = mt_rand(100000, 999999);
        }

        return $serialNumber;
    }

    /**
     * Check if the store branch has an active work status.
     */
    public function hasActiveWorkStatus()
    {
        return $this->activeWorkStatus()->exists();
    }

    /**
     * Get the work status start time formatted as a string.
     */
    public function getWorkStartTimeFormattedAttribute()
    {
        return isset($this->work_status->start_time) ? $this->work_statu?->start_time->format('H:i') : null;
    }

    /**
     * Get the work status end time formatted as a string.
     */
    public function getWorkEndTimeFormattedAttribute()
    {
        return isset($this->work_status->end_time) ? $this->work_status->end_time->format('H:i') : null;
    }

    /**
     * Get the work status end time formatted as a string.
     */
    public function getFavouredByCustomersAttribute($customerId)
    {
        // Return false if the provided $customerId is null
        if (is_null($customerId)) {
            return false;
        }

        // Check if the customer is favoured
        return $this->favoured_by_customers()->where('customer_id', $customerId)->exists();
    }

    public function branchIsWorkingNow()
    {
        if ($this->work_statuses->count()) {
            $branchWorkStatus = $this->work_statuses[0];

            if (isset($branchWorkStatus->start_time) && isset($branchWorkStatus->end_time)) {
                $nowInLocalTimezone = now(request('timezone'));
                $startTime = now(request('timezone'))->setTimeFromTimeString($branchWorkStatus->start_time);
                $endTime = now(request('timezone'))->setTimeFromTimeString($branchWorkStatus->end_time);

                return ($branchWorkStatus->status == 'active')
                    && $nowInLocalTimezone->greaterThanOrEqualTo($startTime)
                    && $nowInLocalTimezone->lessThanOrEqualTo($endTime);
            }

        } else {
            return null;
        }
    }
}
