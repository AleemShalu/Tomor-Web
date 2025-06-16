<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Import the NotificationType model if not already imported

// Import the User model if not already imported

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed notifications for type 1 (indexNotificationApp)
        $this->seedNotifications(1, 10);

        // Seed notifications for type 2 (indexNotificationStore)
        $this->seedNotifications(2, 10);
    }

    private function seedNotifications($type, $count)
    {
        // Define the user_id for which notifications should be created
        $user_id = 1; // Change this to the desired user ID

        // Define the data for the notifications
        $notificationsData = [];

        for ($i = 0; $i < $count; $i++) {
            $notificationsData[] = [
                'type' => $type,
                'channel' => 'mail, database, whatsapp, app, web',
                'title' => 'Your Custom Subject Here',
                'message' => 'Test notification message',
                'sender_id' => 1,
                'recipient_id' => 4,
                'read_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert the notifications into the database
        foreach ($notificationsData as $data) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'notifiable_id' => $user_id,
                'notifiable_type' => 'App\\Models\\User',
                'type' => 'App\\Notifications\\SendMessage',
                'data' => json_encode($data), // Encode data as JSON
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }
    }
}