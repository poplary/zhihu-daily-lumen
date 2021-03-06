<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ZhihuCrawlCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // 每天定时抓取知乎数据
        $schedule->command('zhihu:crawl')
            ->everyThirtyMinutes()
            ->when(function () {
                return date('H') >= 6 && date('H') <= 19;
            })
            ->timezone('Asia/Shanghai');
    }
}
