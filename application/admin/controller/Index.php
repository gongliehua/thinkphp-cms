<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\Permissions;
use app\common\controller\Backend;
use think\facade\Session;

/*
 * 后台首页
 */
class Index extends Backend
{
    /**
     * 后台首页
     * @return mixed
     */
	public function index()
	{
	    // 默认管理员获取所有菜单
	    if (Session::get('admin_id') == 1) {
            $admin = Admin::with(['adminRole'=>['role']])->where('id', Session::get('admin_id'))->find();
	        $permissions = multi_array_html(sort_multi_array(json_decode(json_encode(Permissions::where('menu', 1)->order('sort', 'asc')->select()), true)));
        } else {
            $admin = Admin::with(['adminRole'=>['role'=>['permissions'=>function($query) {
                $query->where('menu', 1)->order('sort', 'asc');
            }]]])->where('id', Session::get('admin_id'))->find();
            $permissions = multi_array_html(sort_multi_array(json_decode(json_encode($admin->admin_role->role->permissions), true)));
        }
	    $this->assign(compact('admin', 'permissions'));
	    return $this->fetch();
    }
}
