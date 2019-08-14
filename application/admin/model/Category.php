<?php

namespace app\admin\model;

use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['type_text'];

    public function getTypeTextAttr($value, $data)
    {
        $type = [1=>'列表', 2=>'单页', 3=>'链接'];
        return isset($type[$data['type']]) ? $type[$data['type']] : $data['type'];
    }

    public function category()
    {
        return $this->hasOne('Category', 'parent_id', 'id');
    }

    public function article()
    {
        return $this->hasOne('Article', 'category_id', 'id');
    }
}
