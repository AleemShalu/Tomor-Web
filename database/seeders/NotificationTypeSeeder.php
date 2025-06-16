<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Define your notification types
        $notificationTypes = [
            ['name' => 'Admin'],
            ['name' => 'Owner'],
            ['name' => 'Customer'],
            ['name' => 'Worker'],
            // add more notification types as needed
        ];

        // Insert each notification type if it does not exist
        foreach ($notificationTypes as $type) {
            NotificationType::firstOrCreate($type);
        }

        // Output success message
        $this->command->info('Notification types inserted or already exist.');
    }
}
