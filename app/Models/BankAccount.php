<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Base
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function branches()
    {
        return $this->hasMany(StoreBranch::class, 'bank_account_id');
    }
}
