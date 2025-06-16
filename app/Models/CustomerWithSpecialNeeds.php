<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerWithSpecialNeeds extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function special_needs_type()
    {
        return $this->belongsTo(SpecialNeedsType::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
