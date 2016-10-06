<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Services\ZhihuDailyApiService;
use Illuminate\Http\Request;

class ZhihuController extends BaseController
{
    /**
     * 构造方法
     */
    public function __construct()
    {

    }

    public function latest(Request $request)
    {
        $skip = (int)$request->input('skip') or 0;
        $zhihu = new ZhihuDailyApiService();
        $data = $zhihu->latest($skip);

        if(! $data) {
            return response()->json([
                    'errcode' => 40201,
                    'errmsg' => '获取不到数据'
                ], 404);
        }

        return response()->json([
                'errcode' => 0,
                'errmsg' => '获取成功',
                'list' => $data
            ], 200);
    }

    public function day(Request $request, $date)
    {
        $date = date('Ymd', strtotime($date));
        $zhihu = new ZhihuDailyApiService();
        $data = $zhihu->someday($date);
        
        if(! $data) {
            return response()->json([
                    'errcode' => 40201,
                    'errmsg' => '获取不到数据'
                ], 404);
        }

        return response()->json([
                'errcode' => 0,
                'errmsg' => '获取成功',
                'list' => $data
            ], 200);

    }
}