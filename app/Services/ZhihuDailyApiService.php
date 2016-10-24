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

    private function filter($data)
    {
        $filter = [];
        foreach ($data as $k => $v) {
            $filter[$k] = [
                'title' => $v->title,
                'url' => zhihuDailyUrl($v->story_id),
                'image' => imageUrl($v->image),
                'date' => date('Y-m-d', strtotime($v->date)),
            ];
        }

        return $filter;
    }

    public function latest($offset = 0, $count = 20)
    {
        $zhihu = new ZhihuDaily();

        $offset = intval($offset);

        $data = $zhihu::orderBy('id', 'desc')->skip($offset)->take($count)->get();

        return $this->filter($data);
    }

    public function history($date)
    {
        $data = ZhihuDaily::where('date', $date)->get();

        return $this->filter($data);
    }
}
