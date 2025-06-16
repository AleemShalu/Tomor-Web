<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorePromoter extends Base
{
    use HasFactory;

    protected $fillable = [
        'code',
        'store_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'promoter_header_path',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
