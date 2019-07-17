<?php

namespace app\http\middleware;

use app\admin\model\Admin;
use think\facade\Session;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('admin_id')) {
            return redirect('admin/Login/index');
        }
        $admin = Admin::where('id', Session::get('admin_id'))->value('id');
        if (!$admin) {
            Session::clear();
            return redirect('admin/Login/index');
        }
    	return $next($request);
    }
}
