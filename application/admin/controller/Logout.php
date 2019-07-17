<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Logout extends Controller
{
	public function index()
	{
	    Session::clear();
    }
}
