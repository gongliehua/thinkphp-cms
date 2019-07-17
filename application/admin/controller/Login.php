<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Login extends Controller
{
	public function index()
	{
	    if (!Session::has('admin_id')) {
            Session::set('admin_id', 1);
        }
    }
}
