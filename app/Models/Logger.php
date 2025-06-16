<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Logger.php

namespace App\Models;


class Logger extends Base
{

    protected $fillable = [
        'path',
        'method',
        'ip',
        'http_version',
        'timestamp',
        'headers',
        'request',
        'response',
        'user_id',
        'model',
    ];

    protected $casts = [
        'headers' => 'array',
        'request' => 'array',
        'response' => 'array',
    ];
}
