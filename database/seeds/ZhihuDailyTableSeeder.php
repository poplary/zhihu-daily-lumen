<?php

use Illuminate\Database\Seeder;

class ZhihuDailyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('zhihu_dailies')->insert([
            [
                'date' => 20150101,
                'story_id' => 4416804,
                'title' => '瞎扯 · 如何正确地吐槽',
                'type' => 0,
                'multipic' => 0,
                'ga_prefix' => '010106',
                'image_origin' => 'http://pic2.zhimg.com/b07d17485ce8c8c05802bfe906c14a1b.jpg',
                'image' => '4416804.jpg',
            ],
            [
                'date' => 20150101,
                'story_id' => 4412256,
                'title' => '《自由引导人民》为什么一定要露胸？（多图）',
                'type' => 0,
                'multipic' => 0,
                'ga_prefix' => '010107',
                'image_origin' => 'http://pic2.zhimg.com/dd078df8fca51745be58aa885ea49b69.jpg',
                'image' => '4412256.jpg',
            ],
            [
                'date' => 20150101,
                'story_id' => 4413818,
                'title' => '东伦敦的魅力，没法在东伦敦找到（多图）',
                'type' => 0,
                'multipic' => 0,
                'ga_prefix' => '010107',
                'image_origin' => 'http://pic4.zhimg.com/b7dc6fcaf5163f145ccf8342b3fc300e.jpg',
                'image' => '4413818.jpg',
            ],
            [
                'date' => 20150101,
                'story_id' => 4151427,
                'title' => '为什么火车票难买？',
                'type' => 0,
                'multipic' => 0,
                'ga_prefix' => '010107',
                'image_origin' => 'http://pic4.zhimg.com/7449b76176e6b2ce8d4f689d84d14a9c.jpg',
                'image' => '4151427.jpg',
            ],
        ]);
    }
}
