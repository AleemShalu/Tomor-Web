<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsherClient extends Base
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'usher_id',
        'code_usher_used',
    ];

    /**
     * Get the user associated with the UsherClient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the store associated with the UsherClient.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the usher associated with the UsherClient.
     */
    public function usher()
    {
        return $this->belongsTo(Usher::class);
    }
}
