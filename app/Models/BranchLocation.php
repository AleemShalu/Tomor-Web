<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchLocation extends Base
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'store_branch_id',
        'location_description',
        'google_maps_url',
        'longitude',
        'latitude',
        'district',
        'location_radius',
    ];

    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class);
    }
}
