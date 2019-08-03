<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Logout extends Controller
{
	public function index()
	{
	    Session::delete('admin_id');
	    $this->success('退出登录', 'Login/index');
    }
}
