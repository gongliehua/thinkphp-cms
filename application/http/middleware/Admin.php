<?php

namespace app\http\middleware;

use app\admin\model\Admin as AdminModel;
use think\facade\Session;

class Admin
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('admin_id')) {
            return redirect('admin/Login/index');
        }
        $admin = AdminModel::where('id', Session::get('admin_id'))->value('id');
        if (!$admin) {
            Session::delete('admin_id');
            return redirect('Login/index');
        }
    	return $next($request);
    }
}
