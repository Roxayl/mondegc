<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Roxayl\MondeGC\Jobs\StoreResourceHistory;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $storeResourceHistory = app()->make(StoreResourceHistory::class);
        $schedule->job($storeResourceHistory)
            ->twiceMonthly(1, 15, '10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
