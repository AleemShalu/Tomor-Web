<?php

namespace App\Models;


class WorkDay extends Base
{
    protected $fillable = ['employee_id', 'work_date', 'start_time', 'end_time'];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime ::class);
    }
}
