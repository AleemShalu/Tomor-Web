<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Business extends Base
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'vat_number',
        'group_vat_number',
        'cr_number',
        'email',
        'country_code',
        'phone',
        'country',
        'state',
        'city',
        'district',
        'street',
        'building_no',
        'zipcode',
        'logo',
        'website',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];
}