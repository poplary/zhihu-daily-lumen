<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZhihuDaily extends Model
{
    // 软删除
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
