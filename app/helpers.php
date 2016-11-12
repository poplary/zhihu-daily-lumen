<?php

function imageUrl($fileName, $source = 'zhihu')
{
    $filePath = base_path('public/assets/img/'.$source.'/'.$fileName);

    // 若文件不存在或者文件名为空，输出默认的图片
    if (!file_exists($filePath) || !$fileName) {
        return env('BASE_URL').'/assets/img/'.$source.'/default.jpg';
    }
    $imageUrl = env('BASE_URL').'/assets/img/'.$source.'/'.ltrim($fileName, '/');

    return $imageUrl;
}

function zhihuDailyUrl($storyId)
{
    $zhihuDailyUrl = env('ZHIHUDAILY_URL').'/'.$storyId;

    return $zhihuDailyUrl;
}

function apiUrl($value)
{
    $url = env('BASE_URL').'/'.$value;

    return $url;
}
