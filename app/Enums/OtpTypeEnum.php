<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OtpTypeEnum : string
{
    use EnumTrait;

    case REGISTRATION = 'registration';

    case LOGIN = 'login';

    case VERIFICATION = 'verification';

    case FORGET_PASSWORD = 'forget_password';

}
