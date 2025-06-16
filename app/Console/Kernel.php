<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('payments:check-pending')
            ->everyMinute()
            ->sendOutputTo(storage_path('logs/schedule.log'));

            $schedule->command('orders:find-refund-eligible')
                 ->everyMinute()
                 ->timezone('Asia/Riyadh')
                 ->appendOutputTo(storage_path('logs/refund-eligible.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
