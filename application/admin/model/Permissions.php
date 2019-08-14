<?php

namespace app\admin\model;

use think\Model;

class Permissions extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['menu_text'];

    public function getMenuTextAttr($value, $data)
    {
        return $data['menu'] ? '是' : '否';
    }

    public function permissions()
    {
        return $this->hasOne('Permissions', 'parent_id', 'id');
    }
}
