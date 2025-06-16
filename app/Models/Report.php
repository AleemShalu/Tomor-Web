<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Base
{
    use HasFactory;

    /**
     * @var false|mixed|string
     */
    public mixed $attachments;
    protected $guarded;

    public function report_subtype()
    {
        return $this->belongsTo(ReportSubtype::class);
    }
}
