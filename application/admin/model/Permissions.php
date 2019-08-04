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
}
