<?php

namespace App\Notifications\Channels;
use UltraMsg\WhatsAppApi;
use App\Notifications\Api\PhoneOtpNotification;

class WhatsAppChannel
{
    /**
     * Send the given notification.
    */
    public function send(object $notifiable, PhoneOtpNotification $notification): void
    {
        // connect to ultramessage service with token and instance id.
        $ultramsg_token = env('ULTRAMSG_TOKEN');
        $ultramsg_instance_id = env('ULTRAMSG_INSTANCE_ID');

        $body = __(
            'locale.api.auth.otp_code.otp_message',
            ['code' => $notification->user_data->code],
            $notification->user_data->device_locale ?? app()->getLocale() ?? config('app.locale', 'en'),
        );

        // $to = $notifiable->getUserPhoneNumber();
        // Log::alert($to);
        // Log::alert($body);

        $client = new WhatsAppApi($ultramsg_token, $ultramsg_instance_id);
        $to = $notification->user_data->dial_code . $notification->user_data->contact_no;

        $client->sendChatMessage($to, $body);
    }
}
