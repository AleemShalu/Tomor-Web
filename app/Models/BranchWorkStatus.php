<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchWorkStatus extends Base
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'store_branch_id',
        'status',
        'start_time',
        'end_time',
        'store_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function branch()
    {
        return $this->belongsTo(StoreBranch::class);
    }

    public function getStartTimeAttribute($value)
    {
        if (isset($value) && request('timezone')) {
            return convertLocalTimeToConfiguredTimezone($value, config('app.timezone'), request('timezone'));
        }

        return $value;
    }

    public function getEndTimeAttribute($value)
    {
        if (isset($value) && request('timezone')) {
            return convertLocalTimeToConfiguredTimezone($value, config('app.timezone'), request('timezone'));
        }

        return $value;
    }
}
