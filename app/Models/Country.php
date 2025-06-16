<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
