<?php

namespace app\admin\model;

use think\Model;

/*
 * 用户角色关联表
 */
class AdminRole extends Model
{
    protected $autoWriteTimestamp = true;

    // 模型关联,获取对于角色信息
    public function role()
    {
        return $this->belongsTo('Role', 'role_id', 'id');
    }

    // 模型关联,获取对于用户信息
    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id');
    }
}
