<?php

namespace App\Console;

use App\Console\Commands\ScrapeStart;
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
        ScrapeStart::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('scrape:start')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scrape-start.log'))
            ->runInBackground()
        ;

        $schedule->command('cache:truncate')
            ->daily()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cache-truncate.log'))
            ->runInBackground()
        ;

        $schedule->command('settings:update')
            ->daily()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/settings-update.log'))
            ->runInBackground()
        ;

        $schedule->command('ip:update')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/ip-update.log'))
            ->runInBackground()
        ;

        $schedule->command('search:index')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/search-index.log'))
            ->runInBackground()
        ;
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
