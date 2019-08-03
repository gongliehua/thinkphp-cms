<?php

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $autoWriteTimestamp = true;

    public function permissions()
    {
        return $this->belongsToMany('Permissions', 'RolePermissions', 'permissions_id', 'role_id');
    }
}
