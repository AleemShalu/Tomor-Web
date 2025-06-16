<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TimezoneEnum : string
{
    use EnumTrait;

    case SA_TIMEZONE = 'Asia/Riyadh';

    case UTC_TIMEZONE = 'UTC';

    public static function DifHours(): array
    {
        return [
            self::SA_TIMEZONE->value => '+03:00',
            self::UTC_TIMEZONE->value => '+00:00',
        ];
    }
}
