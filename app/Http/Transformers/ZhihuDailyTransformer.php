<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ZhihuDaily;

/**
 * Class ZhihuDailyTransformer
 * @package App\Http\Transformers
 */
class ZhihuDailyTransformer extends TransformerAbstract
{
    /**
     * @param ZhihuDaily $zhihuDaily
     * @return array
     */
    public function transform(ZhihuDaily $zhihuDaily)
    {
        return [
            'title' => $zhihuDaily->title,
            'url' => zhihuDailyUrl($zhihuDaily->story_id),
            'image' => imageUrl($zhihuDaily->image),
            'date' => date('Y-m-d', strtotime($zhihuDaily->date)),
        ];
    }
}
