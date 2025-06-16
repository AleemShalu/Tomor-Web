<?php

namespace App\Models;

class BreakTime extends Base
{
    protected $fillable = ['work_day_id', 'break_start_time', 'break_end_time'];

    public function workDay()
    {
        return $this->belongsTo(WorkDay::class);
    }
}
