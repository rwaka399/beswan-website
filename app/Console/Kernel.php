<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto close expired attendances every 5 minutes
        $schedule->command('attendance:auto-close')->everyFiveMinutes();
        
        // Update expired lesson packages daily at midnight
        $schedule->command('packages:update-expired')->daily();
        
        // Activate scheduled packages daily at midnight
        $schedule->command('packages:activate-scheduled')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
