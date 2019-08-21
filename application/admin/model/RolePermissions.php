<?php

namespace app\admin\model;

use think\Model;

/*
 * 角色权限表
 */
class RolePermissions extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 模型关联,获取对于角色的信息
     * @return \think\model\relation\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('Role', 'role_id', 'id');
    }
}
