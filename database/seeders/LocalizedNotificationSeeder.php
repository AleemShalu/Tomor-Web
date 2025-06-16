<?php

namespace Database\Seeders;

use App\Models\LocalizedNotification;
use App\Models\NotificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalizedNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve existing notification types from the database
        $notificationTypes = NotificationType::all();

        // Define sample localized notifications and insert them into the database
        $localizedNotifications = [
            [
                'notification_type_id' => $notificationTypes->random()->id,
                'locale' => 'en',
                'name' => 'English Notification 1',
                'description' => 'Description for English Notification 1',
            ],
            [
                'notification_type_id' => $notificationTypes->random()->id,
                'locale' => 'fr',
                'name' => 'French Notification 1',
                'description' => 'Description for French Notification 1',
            ],
            // add more sample localized notifications as needed
        ];

        foreach ($localizedNotifications as $localizedNotification) {
            LocalizedNotification::create($localizedNotification);
        }
    }
}
