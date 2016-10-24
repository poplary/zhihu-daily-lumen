<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Services\ZhihuDailyApiService;
use Illuminate\Http\Request;

class ZhihuController extends BaseController
{
    /**
     * 构造方法.
     */
    public function __construct()
    {
    }

    /**
     * 获取最新的数据.
     *
     * @param Request $request 请求，offset之类的数据
     *
     * @return json 最新的数据
     */
    public function latest(Request $request)
    {
        $offset = (int) $request->input('offset') ?: 0;
        $zhihu = new ZhihuDailyApiService();
        $data = $zhihu->latest($offset);

        if (!$data) {
            return response()->json($this->returnData(40201, '获取不到数据'), 404);
        }

        return response()->json($this->returnData(0, '获取成功', $data), 200);
    }

    /**
     * 获取历史数据.
     *
     * @param Request $request 请求的数据
     * @param string  $date    日期
     *
     * @return json 请求日期当天的数据
     */
    public function history(Request $request, $date)
    {
        $date = date('Ymd', strtotime($date));
        $zhihu = new ZhihuDailyApiService();
        $data = $zhihu->history($date);

        if (!$data) {
            return response()->json($this->returnData(40201, '获取不到数据，请确保日期正确（2015-01-01 至今）'), 404);
        }

        return response()->json($this->returnData(0, '获取成功', $data), 200);
    }
}
