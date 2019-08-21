<?php

namespace app\admin\model;

use think\Model;

/*
 * 权限表
 */
class Permissions extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['menu_text'];

    /**
     * 获取是否菜单的文本
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getMenuTextAttr($value, $data)
    {
        return $data['menu'] ? '是' : '否';
    }

    // 模型关联,获取上级权限
    public function permissions()
    {
        return $this->hasOne('Permissions', 'parent_id', 'id');
    }
}
