<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationConfig extends Base
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'code',
        'name',
        'unit',
        'max_radius',
        'min_radius',
    ];
}
