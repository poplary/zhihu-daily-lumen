<?php

namespace App\Services;

use App\Models\ZhihuDaily;

/**
 * 知乎日报本地API.
 */
class ZhihuDailyApiService
{
    /**
     * 构造方法.
     */
    public function __construct()
    {
    }

    public function latest($page = 1, $count = 20)
    {
        $data = ZhihuDaily::orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);

        return $data;
    }

    public function history($date)
    {
        $data = ZhihuDaily::where('date', $date)->get();

        return $data;
    }
}
