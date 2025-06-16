<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTerms extends Base
{
    use HasFactory;

    protected $table = 'store_terms';

    protected $fillable = [
        'body_ar',
        'body_en',
        'issued_at',
        'expired_at',
    ];

}
