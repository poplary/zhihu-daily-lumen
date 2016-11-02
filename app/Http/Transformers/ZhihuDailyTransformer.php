<?php

namespace App\Http\Transformers;

use App\Models\ZhihuDaily;
use League\Fractal\TransformerAbstract;

/**
 * Class ZhihuDailyTransformer.
 */
class ZhihuDailyTransformer extends TransformerAbstract
{
    /**
     * @param ZhihuDaily $zhihuDaily
     *
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
