<?php

namespace App\Services;

use App\Models\ZhihuDaily;

/**
 * 知乎日报本地API
 */
class ZhihuDailyApiService{

    /**
     * 构造方法
     */
    public function __construct()
    {

    }

    private function filter($data)
    {
        $filter = [];
        foreach($data as $k => $v) {
            $filter[$k] = [
                'title' => $v->title,
                'url' => zhihuDailyUrl($v->story_id),
                'image' => imageUrl($v->image),
                'date' => date('Y-m-d', strtotime($v->date))
            ];
        }
        return $filter;
    }

    public function latest($skip=0, $count=20)
    {

        $zhihu = new ZhihuDaily;

        $skip = intval($skip);

        $data = $zhihu::orderBy('id', 'desc')->skip($skip)->take($count)->get();
        return $this->filter($data);
    }

    public function someday($date)
    {
        $data = ZhihuDaily::where('date', $date)->get();
        return $this->filter($data);
    }
}
