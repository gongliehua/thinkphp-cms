<?php

namespace app\admin\model;

use think\Model;

class Config extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['type_text'];

    public function getTypeTextAttr($value, $data)
    {
        $type = [1=>'单行文本', 2=>'多行文本', 3=>'单选按钮', 4=>'复选框', 5=>'下拉框'];
        return isset($type[$data['type']]) ? $type[$data['type']] : $data['type'];
    }
}
