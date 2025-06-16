<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchVisitor extends Base
{
    use HasFactory;


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branch_id');
    }


}
