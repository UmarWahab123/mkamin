<?php

namespace App\Console;

use App\Console\Commands\GenerateStaffTimeIntervals;
use App\Console\Commands\GeneratePosTimeIntervals;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateStaffTimeIntervals::class,
        GeneratePosTimeIntervals::class,
    ];

    // Run this command in linux terminal: nohup /usr/local/bin/php artisan schedule:work > storage/logs/scheduler.log 2>&1 &
    // [1] 639404          this is the process id and if you want to stop the scheduler you can run: kill 639404

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the staff time intervals generation command every day at midnight
        $schedule->command('staff:generate-time-intervals')->dailyAt('00:00');

        // Run the POS time intervals generation command every day at 00:30
        $schedule->command('pos:generate-time-intervals')->dailyAt('00:30');
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
