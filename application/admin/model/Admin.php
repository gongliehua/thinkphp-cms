<?php

namespace app\admin\model;

use think\Model;

/*
 * 用户表
 */
class Admin extends Model
{
    protected $autoWriteTimestamp = true;

    // 模型关联,获取用户角色一对一关系
    public function adminRole()
    {
        return $this->hasOne('AdminRole', 'admin_id', 'id');
    }
}
