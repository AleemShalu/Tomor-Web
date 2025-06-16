<?php

namespace App\Enums\Dhamen;

use App\Traits\EnumTrait;

enum NotificationTypeEnum: string
{
    use EnumTrait;

    case DEPOSIT_NOTIFICATION = 'Deposit_Notification';
    case FULL_PAYMENT_NOTIFICATION = 'Full_Payment_Notification';
    case FUNDS_TRANSFERRING_NOTIFICATION = 'Funds_Transferring_Notification';
    case FAILURE_TRANSFER_NOTIFICATION = 'Failure_Transfer_Notification';
    case RESEND_FAILURE_TRANSFER_NOTIFICATION = 'Resend_Failure_Transfer_Notification';


    public static function labels(): array
    {
        return [
            self::DEPOSIT_NOTIFICATION->value => __('locale.api.notification-type.labels.deposit_notification'),
            self::FULL_PAYMENT_NOTIFICATION->value => __('locale.api.notification-type.labels.full_payment_notification'),
            self::FUNDS_TRANSFERRING_NOTIFICATION->value => __('locale.api.notification-type.labels.funds_transferring_notification'),
            self::FAILURE_TRANSFER_NOTIFICATION->value => __('locale.api.notification-type.labels.failure_transfer_notification'),
            self::RESEND_FAILURE_TRANSFER_NOTIFICATION->value => __('locale.api.notification-type.labels.resend_failure_transfer_notification'),
        ];
    }

}
