<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckExpiryAndSendEmails::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Daily run at 9 AM
        $schedule->command('expiry:check-and-mail')->dailyAt('09:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
