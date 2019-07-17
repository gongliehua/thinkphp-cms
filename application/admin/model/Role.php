<?php

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $autoWriteTimestamp = true;

    public function adminRole()
    {
        return $this->belongsToMany('Permissions', 'RolePermissions', 'role_id', 'permissions_id');
    }
}
