<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialNeedsType extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function customers()
    {
        return $this->hasMany(CustomerWithSpecialNeeds::class, 'special_needs_type_id');
    }
}
