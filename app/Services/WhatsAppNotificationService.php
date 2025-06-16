<?php

namespace App\Services;

use UltraMsg\WhatsAppApi;

class WhatsAppNotificationService
{
    public function sendWhatsAppMessage($to, $message)
    {
        // connect to ultramessage service with token and instance id.
        $ultramsg_token = env('ULTRAMSG_TOKEN');
        $ultramsg_instance_id = env('ULTRAMSG_INSTANCE_ID');
        $client = new WhatsAppApi($ultramsg_token, $ultramsg_instance_id);

        $client->sendChatMessage($to, $message);
    }
}