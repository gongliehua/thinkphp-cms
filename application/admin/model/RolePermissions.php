<?php

namespace app\admin\model;

use think\Model;

class RolePermissions extends Model
{
    protected $autoWriteTimestamp = true;

    public function role()
    {
        return $this->belongsTo('Role', 'role_id', 'id');
    }
}
