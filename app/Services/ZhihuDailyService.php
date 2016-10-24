<?php

namespace App\Services;

use App\Models\ZhihuDaily;
use Openbuildings\Spiderling\Page;

/**
 * 知乎日报.
 */
class ZhihuDailyService
{
    /**
     * 最新消息
     * date : 日期
     * stories : 当日新闻
     * title : 新闻标题
     * image : 图像地址
     * share_ur: 供在线查看内容与分享至 SNS 用的 URL
     * ga_prefix : 供 Google Analytics 使用.
     */
    private $latestUrl = 'http://news-at.zhihu.com/api/3/news/latest';

    /**
     * 内容获取
     * body : HTML日报内容
     * image-source : 图片内容提供方
     * title :日报标题
     * image : 文章大图.
     */
    private $articleUrl = 'http://news-at.zhihu.com/api/3/news/';

    /**
     * 以往内容
     * 若果需要查询 6 月20 日的消息，before 后的数字应为 20140621
     * 知乎日报的生日为 2013 年 5 月 19 日，若before后数字小于 20130520，只会接收到空消息
     * 输入的今日之后的日期仍然获得今日内容，但是格式不同于最新消息的 JSON 格式.
     */
    private $beforeUrl = 'http://news-at.zhihu.com/api/3/news/before/';

    /**
     * 热门消息
     * 获得到的图片地址，不再使用image 而是thumbnail.
     */
    private $hotUrl = 'http://news-at.zhihu.com/api/3/news/hot';

    /**
     * 构造方法.
     */
    public function __construct()
    {
    }

    /**
     * 发送请求
     *
     * @param string $url    请求链接
     * @param array  $params 请求参数
     *
     * @return array 回应消息
     */
    private function httpGet($url, $params = [])
    {
        $page = new Page();
        try {
            $page->visit($url);
        } catch (\Openbuildings\Spiderling\Exception_Curl $e) {
            dd($e->getMessage());

            return;
        }

        $data = json_decode($page->content());

        return $data;
    }

    /**
     * 获取最新知乎日报数据.
     *
     * @return array 知乎日报最新数据
     */
    public function latest()
    {
        $url = $this->latestUrl;
        $response = $this->httpGet($url);

        return $response;
    }

    /**
     * 获取某天知乎日报数据.
     *
     * @param string $date 日期
     *
     * @return array 知乎日报当天数据
     */
    public function someday($date)
    {
        $url = $this->beforeUrl.$date;
        $response = $this->httpGet($url);
        // 存储当天数据
        $this->store($response);

        return $response;
    }

    /**
     * 存储数据.
     *
     * @param object $data 存储的数据
     */
    public function store($data)
    {
        if (!$data) {
            echo '获取失败！'.PHP_EOL;

            return;
        }

        $date = $data->date;
        $insertData = [];
        foreach ($data->stories as $k => $v) {
            $insertData[$k] = [
                'date' => $date,
                'story_id' => $v->id,
                'title' => $v->title,
                'type' => $v->type,
                'multipic' => $v->type or null,
                'ga_prefix' => $v->ga_prefix,
                'image_origin' => isset($v->images) ? $v->images['0'] : '',
            ];
        }
        unset($v);

        // 颠倒顺序并执行
        foreach (array_reverse($insertData) as $v) {
            if (!ZhihuDaily::where('story_id', $v['story_id'])->first()) {
                // 存储数据
                $zhihuDaily = new ZhihuDaily();
                $zhihuDaily->date = $v['date'];
                $zhihuDaily->story_id = $v['story_id'];
                $zhihuDaily->title = $v['title'];
                $zhihuDaily->type = $v['type'];
                $zhihuDaily->multipic = $v['multipic'];
                $zhihuDaily->ga_prefix = $v['ga_prefix'];
                $zhihuDaily->image_origin = $v['image_origin'];
                $zhihuDaily->save();
            }
        }
        echo $date.' 数据获取成功！'.PHP_EOL;
    }

    /**
     * 获取数据库中已有的最后日期
     * 若无数据，则为20141231，只获取20150101年之后的数据.
     *
     * @return string 最后的日期
     */
    public function latestDate()
    {
        // 由于获取的最后日期
        if (ZhihuDaily::count()) {
            return ZhihuDaily::max('date');
        } else {
            return '20150101';
        }
    }

    /**
     * 获取知乎日报的数据
     * 开始时间为数据库已有的最后时间或者20150101
     * 结束时间为当前时间的第二天.
     *
     * @param object $data 存储的数据
     */
    public function getZhihuDaily($days = 0)
    {

        // 获取数据库中已有的最后日期
        $beginDate = $this->latestDate();

        // 数据库中的日期 +1，例如最后日期为 20150101，则得到 20150102，
        // API 获取的是传入日期前一天的数据，
        // 通过 API 传入 20150102 将获取到 20150101 的数据
        $time = strtotime($beginDate) + 24 * 60 * 60;

        // 传入明天的日期则会获取今天的数据
        $tommorowTime = strtotime('+1 day');

        $endTime = $days > 0 ? ($time + 24 * 60 * 60 * $days) : $tommorowTime;

        while ($time < $endTime) {
            $date_str = date('Ymd', $time);
            $this->someday($date_str);
            $time += 24 * 60 * 60;
        }
    }

    /**
     * 获取图片存储到本地，以知乎日报id命名图片.
     *
     * @param string $url  图片url
     * @param string $id   知乎日报id
     * @param int    $type 获取类型：1为直接获取，2通过curl获取
     *
     * @return string 文件名
     */
    public function getImage($url, $id, $type = 1)
    {
        $ext = strrchr($url, '.');

        $fileName = $id.$ext;
        $filePath = base_path('public/assets/img/zhihu/'.$fileName);

        if ($type === 1) {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        } elseif ($type === 2) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0');
            $img = curl_exec($ch);
            curl_close($ch);
        }

        $fp = @fopen($filePath, 'a');
        $size = fwrite($fp, $img);
        if ($size) {
            $zhihu = ZhihuDaily::where('story_id', $id)->first();
            $zhihu->image = $fileName;
            $zhihu->save();
            echo $id.' 获取图片成功！'.PHP_EOL;
        }
        fclose($fp);

        return $fileName;
    }

    /**
     * 查找数据库，批量获取本地未存的图片.
     */
    public function getZhihuImageBatch()
    {
        // 获取20150101之后的图片
        $zhihuDailyData = ZhihuDaily::where('image_origin', '!=', '')
                                    ->where('image', '')
                                    ->where('date', '>=', 20150101)
                                    ->orderBy('id', 'desc')
                                    ->get();
        foreach ($zhihuDailyData as $v) {
            // $this->getImage($v->image_origin, $v->story_id, 1);
            $this->getImage($v->image_origin, $v->story_id, 2);
        }
    }
}
