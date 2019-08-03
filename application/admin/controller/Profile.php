<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\common\controller\Backend;
use think\facade\Session;

class Profile extends Backend
{
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'username|用户名'=>'require|length:3,50|unique:admin,username,'.Session::get('admin_id').',id|token',
                'password|密码'=>'length:6,50',
                'avatar|头像'=>'file',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 获取用户信息
            $admin = Admin::where('id', Session::get('admin_id'))->find();
            // 用来删除头像,不能全赋值,$admin可能是个单例模式对象
            $origin = $admin['avatar'];

            // 判断文件是否存在
            if (!empty($_FILES['avatar']['name'])) {
                $file = request()->file('avatar');
                $info = $file->validate(['ext'=>'bmp,jpg,jpeg,png,gif'])->move( './uploads');
                if($info){
                    $data['avatar'] = '/uploads/'.$info->getSaveName();
                    $data['avatar'] = str_replace('\\', '/', $data['avatar']);
                }else{
                    $this->error($file->getError());
                }
            } else {
                $data['avatar'] = '';
            }

            $admin->username = $data['username'];
            if (!empty($data['password'])) {
                $admin->password = sha1($data['password']);
            }
            if (!empty($data['avatar'])) {
                $admin->avatar = $data['avatar'];
            }
            if (!$admin->save()) {
                if (!empty($data['avatar'])) {
                    unset($info);
                    @unlink($_SERVER['DOCUMENT_ROOT'].$data['avatar']);
                }
                $this->error('修改失败');
            }

            // 判断头像是否被更换
            if ($admin['avatar'] != $origin) {
                @unlink($_SERVER['DOCUMENT_ROOT'].$origin);
            }

            $this->success('修改成功', 'Profile/index');
        }

        $info = Admin::with(['adminRole'=>['role']])->where('id', Session::get('admin_id'))->find();

        $this->assign(compact('info'));
        return $this->fetch();
    }
}
