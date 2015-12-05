<?php

namespace App\Console;

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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\CheckAlertNotifications::class,
        \App\Console\Commands\CalculateStationCAQI::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('alerts:update')->everyThirtyMinutes();
        // $schedule->command('measurement:caqi')->everyThirtyMinutes()->sendOutputTo(dirname(__DIR__) . "/../../logs/caqi-log");
    }
}
