<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usher extends Base
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'code_usher',
    ];

    public function clients()
    {
        return $this->hasMany(UsherClient::class, 'usher_id');
    }


}
