<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    protected $autoWriteTimestamp = true;

    public function adminRole()
    {
        return $this->hasOne('AdminRole', 'admin_id', 'id');
    }
}
