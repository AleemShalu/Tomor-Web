<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum LocaleEnum : string
{
    use EnumTrait;

    case AR_LOCALE = 'ar';

    case EN_LOCALE = 'en';
}
