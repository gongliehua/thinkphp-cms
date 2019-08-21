<?php

namespace app\admin\model;

use think\Model;

/*
 * 配置表
 */
class Config extends Model
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
        $type = [1=>'单行文本', 2=>'多行文本', 3=>'单选按钮', 4=>'复选框', 5=>'下拉框'];
        return isset($type[$data['type']]) ? $type[$data['type']] : $data['type'];
    }
}
