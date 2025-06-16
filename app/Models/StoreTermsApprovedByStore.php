<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTermsApprovedByStore extends Base
{
    use HasFactory;

    protected $table = 'store_terms_approved_by_stores';

    protected $fillable = [
        'store_terms_id',
        'store_id',
        'approved',
    ];
}
