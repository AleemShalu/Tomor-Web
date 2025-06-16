<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function branches(): HasMany
    {
        return $this->hasMany(StoreBranch::class);
    }

}
