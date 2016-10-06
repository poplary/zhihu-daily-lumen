<?php

function imageUrl($fileName, $source='zhihu') {
    $filePath = base_path('public/assets/img/' . $source . '/' . $fileName);
    if(! file_exists($filePath))
        return env('BASE_URL') . '/assets/img/' . $source . '/default.jpg';
    $imageUrl = env('BASE_URL') . '/assets/img/' . $source . '/' . ltrim($fileName, '/');
    return $imageUrl;
}

function zhihuDailyUrl($storyId) {
    $zhihuDailyUrl = env('ZHIHUDAILY_URL') . '/' . $storyId;
    return $zhihuDailyUrl;
}