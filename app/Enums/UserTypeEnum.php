<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum UserTypeEnum : string
{
    use EnumTrait;

    case ADMIN = 'admin';

    case OWNER = 'owner';

    case WORKER = 'worker';

    case WORKER_SUPERVISOR = 'worker_supervisor';

    case SUPERVISOR = 'supervisor';

    case CUSTOMER = 'customer';

    public static function labels(): array
    {
        return [
            self::ADMIN->value => __('locale.api.roles.labels.admin'),
            self::OWNER->value => __('locale.api.roles.labels.owner'),
            self::WORKER->value => __('locale.api.roles.labels.worker'),
            self::WORKER_SUPERVISOR->value => __('locale.api.roles.labels.worker_supervisor'),
            self::SUPERVISOR->value => __('locale.api.roles.labels.worker'),
            self::CUSTOMER->value => __('locale.api.roles.labels.customer'),
        ];
    }
}

