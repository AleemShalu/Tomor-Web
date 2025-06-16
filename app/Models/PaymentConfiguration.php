<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConfiguration extends Model
{
    // Define the table associated with the model
    protected $table = 'payment_configurations';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // If the table does not have timestamp columns, set this to false
    public $timestamps = false;

    // Define the fillable properties if you want to use mass assignment
    protected $fillable = [
        'key',
        'value',
    ];
}