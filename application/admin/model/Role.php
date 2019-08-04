<?php

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $autoWriteTimestamp = true;

    protected $append = ['status_text'];

    public function permissions()
    {
        return $this->belongsToMany('Permissions', 'RolePermissions', 'permissions_id', 'role_id');
    }

    public function getStatusTextAttr($value, $data)
    {
        return $data['status'] ? '启用' : '禁用';
    }
}
