<?php

namespace App\Http\Controllers;
use App\Services\ZhihuDailyService;
class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function crawl()
    {
        $crawl = new ZhihuDailyService();
        // $crawl->someday('20150917');
        // echo $crawl->someday('20150917');
    }

}
