<?php

namespace App\Notifications\Channels;
use App\Notifications\SendMessage;
use UltraMsg\WhatsAppApi;

class WhatsAppSendChannel
{
    /**
     * Send the PhoneOtpNotification via WhatsApp.
     *
     * @param object $notifiable
     * @return void
     */
    public function send(object $notifiable, SendMessage $notification): void
    {
        // connect to ultramessage service with token and instance id.
        $ultramsg_token = env('ULTRAMSG_TOKEN');
        $ultramsg_instance_id = env('ULTRAMSG_INSTANCE_ID');

        // Get the WhatsApp message from the notification
        $body = $notification->getMessage();

        // Initialize the WhatsApp client
        $client = new WhatsAppApi($ultramsg_token, $ultramsg_instance_id);

        // Construct the user's phone number (you may need to adjust this based on your user's phone number format)
        $phone = $notifiable->dial_code . $notifiable->contact_no;

        // Create a WhatsAppApi instance and send the message
        $client->sendChatMessage($phone, $body);
    }

}
