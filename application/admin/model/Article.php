<?php

namespace app\admin\model;

use think\Model;

/*
 * 文章表
 */
class Article extends Model
{
    protected $autoWriteTimestamp = true;

    // 模型关联,获取对于栏目信息
    public function category()
    {
        return $this->belongsTo('Category', 'category_id', 'id');
    }
}
