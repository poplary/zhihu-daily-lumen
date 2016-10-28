<?php


class ApiTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testLatest()
    {
        // 获取当前最新的数据，状态码为 0
        $this->get('/api/zhihu/latest')
            ->seeJson([
                'status' => 0,
                'message' => '获取成功',
            ]);
    }

    /**
     * A basic test example.
     */
    public function testHistory()
    {
        // 获取 2015-01-01 的数据，返回 0 表示获取成功
        $this->get('/api/zhihu/history/20150101')
            ->seeJson([
                'status' => 0,
                'message' => '获取成功',
            ]);

        // 获取明天的数据，返回 40201 表示获取不到
        $tomorrow = date('Ymd', strtotime('tomorrow'));
        $this->get('/api/zhihu/history/'.$tomorrow)
            ->seeJson([
                'status' => 40201,
            ]);
    }
}
