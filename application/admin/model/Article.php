<?php

namespace app\admin\model;

use think\Model;

class Article extends Model
{
    protected $autoWriteTimestamp = true;

    public function category()
    {
        return $this->belongsTo('Category', 'category_id', 'id');
    }
}
