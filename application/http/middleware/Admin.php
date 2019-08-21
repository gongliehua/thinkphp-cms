<?php

namespace app\http\middleware;

use app\admin\model\Admin as AdminModel;
use app\admin\model\Permissions;
use app\admin\model\RolePermissions;
use think\facade\Session;

/*
 * 后台模块鉴权
 */
class Admin
{
    public function handle($request, \Closure $next)
    {
        // 判断是否登录
        if (!Session::has('admin_id')) {
            return redirect('admin/Login/index');
        }
        // 判断该用户是否正常存在
        $admin = AdminModel::with(['adminRole'=>['role']])->where('id', Session::get('admin_id'))->find();
        if (!$admin) {
            Session::delete('admin_id');
            return redirect('Login/index');
        }
        // 权限验证,默认管理员拥有所有权限
        if (Session::get('admin_id') != 1) {
            $except = ['admin/index/index', 'admin/profile/index']; // 白名单路由
            $path = $request->module().'/'.$request->controller().'/'.$request->action(); // 路由地址
            $path = strtolower($path); // 转换小写
            // 排除白名单路由
            if (!in_array($path, $except)) {
                // 判断角色是否正常
                if ($admin->admin_role->role->status != 1) {
                    exit('权限不足');
                }
                // 验证路由
                $permissions = Permissions::where('path', $path)->find();
                if ($permissions) {
                    $rolePermissions = RolePermissions::where('role_id', $admin->admin_role->role_id)->where('permissions_id', $permissions->id)->find();
                    if (!$rolePermissions) {
                        exit('权限不足');
                    }
                }
            }
        }
    	return $next($request);
    }
}
