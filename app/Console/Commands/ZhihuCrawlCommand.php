<?php

namespace App\Console\Commands;

use App\Services\ZhihuDailyService;
use Illuminate\Console\Command;

class ZhihuCrawlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhihu:crawl {days=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Zhihu Daily data';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = (int) $this->argument('days');

        $zhihuDaily = new ZhihuDailyService();
        // 获取知乎日报数据
        $zhihuDaily->getZhihuDaily($days);
        // 获取图片并本地保存
        $zhihuDaily->getZhihuImageBatch();
    }
}
