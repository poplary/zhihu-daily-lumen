<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Transformers\ZhihuDailyTransformer;
use App\Services\ZhihuDailyApiService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ZhihuController extends BaseController
{
    /**
     * @var ZhihuDailyApiService
     */
    protected $zhihuDaily;

    /**
     * 构造方法.
     */
    public function __construct()
    {
        $this->zhihuDaily = new ZhihuDailyApiService();
    }

    /**
     * 获取最新的数据.
     *
     * @param Request $request 请求
     *
     * @return \Dingo\Api\Http\Response 最新的数据
     */
    public function latest(Request $request)
    {
        $page = (int) $request->input('page') ?: 1;
        $data = $this->zhihuDaily->latest($page);

        if (!$data) {
            throw new NotFoundHttpException('获取不到数据.');
        }

        return $this->response->paginator($data, new ZhihuDailyTransformer());
    }

    /**
     * 获取历史数据.
     *
     * @param Request $request 请求的数据
     * @param string  $date    日期
     *
     * @return \Dingo\Api\Http\Response 请求日期当天的数据
     */
    public function history(Request $request, $date)
    {
        $date = (int) date('Ymd', strtotime($date));
        $lastDay = (int) date('Ymd', strtotime($date) - 24 * 3600);
        $nextDay = (int) date('Ymd', strtotime($date) + 24 * 3600);
        $today = (int) date('Ymd');

        $data = $this->zhihuDaily->history($date);

        if ($data->count() < 1) {
            throw new NotFoundHttpException('获取不到数据，请确保日期正确（2015-01-01 至今）.');
        }

        $meta = [
            'lastPageUrl' => null,
            'nextPageUrl' => null,
        ];
        if ($lastDay > 20150101 && $lastDay < $today) {
            $meta['lastPageUrl'] = apiUrl('zhihu/history/'.$lastDay);
        }

        if ($nextDay <= $today && $nextDay > 20150101) {
            $meta['nextPageUrl'] = apiUrl('zhihu/history/'.$nextDay);
        }

        return $this->response->collection($data, new ZhihuDailyTransformer())->setMeta($meta);
    }
}
