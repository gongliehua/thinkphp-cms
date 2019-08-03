<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\facade\Session;

class Login extends Controller
{
	public function index()
	{
	    if ($this->request->isPost()) {
	        $data = $this->request->param();
	        $validate = $this->validate($data, [
	            'username|用户名'=>'require|token',
                'password|密码'=>'require',
            ]);
	        if ($validate !== true) {
	            $this->error($validate);
            }
            $admin = Admin::where('username', $data['username'])->where('password', sha1($data['password']))->value('id');
	        if ($admin) {
	            Session::set('admin_id', $admin);
	            $this->success('登录成功', 'Index/index');
            } else {
	            $this->error('用户名或密码错误');
            }
        }
	    return $this->fetch();
    }
}
