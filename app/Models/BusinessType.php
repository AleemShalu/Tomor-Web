<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessType extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'business_type_id');
    }
}
