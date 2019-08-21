<?php

namespace app\admin\model;

use think\Model;

/*
 * 角色表
 */
class Role extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['status_text'];

    /**
     * 获取状态的文本
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getStatusTextAttr($value, $data)
    {
        return $data['status'] ? '启用' : '禁用';
    }

    // 模型关联,获取一对多的权限信息
    public function permissions()
    {
        return $this->belongsToMany('Permissions', 'RolePermissions', 'permissions_id', 'role_id');
    }
}
