<?php

namespace app\admin\model;

use think\Model;

/*
 * 栏目表
 */
class Category extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['type_text'];

    /**
     * 获取类型的文本
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getTypeTextAttr($value, $data)
    {
        $type = [1=>'列表', 2=>'单页', 3=>'链接'];
        return isset($type[$data['type']]) ? $type[$data['type']] : $data['type'];
    }

    // 模型关联,获取对于上级栏目的信息
    public function category()
    {
        return $this->hasOne('Category', 'parent_id', 'id');
    }

    // 模型关联,获取对于文章的信息,用户判断这个栏目下是否有文章
    public function article()
    {
        return $this->hasOne('Article', 'category_id', 'id');
    }
}
