<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

/*
 * 后台退出登录
 */
class Logout extends Controller
{
    /**
     * 后台退出登录
     */
	public function index()
	{
	    Session::delete('admin_id');
	    $this->success('退出登录', 'Login/index');
    }
}
