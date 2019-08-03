<?php

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use app\admin\model\Role;
use app\admin\model\AdminRole;
use app\common\controller\Backend;
use think\Db;

class Admin extends Backend
{
	public function index()
	{
	    $list = AdminModel::with(['adminRole'=>['role']])->paginate();
	    $this->assign(compact('list'));
	    return $this->fetch();
    }

    public function create()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'username|用户名'=>'require|length:3,50|unique:admin|token',
                'password|密码'=>'require|length:6,50',
                'avatar|头像'=>'file',
                'role_id|角色ID'=>'require|integer|notIn:0',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

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

            // 判断角色是否存在
            $role = Role::where('id', $data['role_id'])->value('id');
            if (!$role) {
                $this->error('角色不存在');
            }

            try {
                Db::startTrans();

                $admin = new AdminModel;
                $admin->username = $data['username'];
                $admin->password = sha1($data['password']);
                if (!empty($data['avatar'])) {
                    $admin->avatar = $data['avatar'];
                }
                if (!$admin->save()) {
                    throw new \Exception('添加失败');
                }

                $adminRole = new AdminRole;
                $adminRole = $adminRole->save([
                    'admin_id'=>$admin->id,
                    'role_id'=>$data['role_id'],
                ]);
                if (!$adminRole) {
                    throw new \Exception('添加失败');
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                if (!empty($data['avatar'])) {
                    unset($info);
                    @unlink($_SERVER['DOCUMENT_ROOT'].$data['avatar']);
                }
                $this->error($e->getMessage());
            }
            $this->success('添加成功', 'Admin/index');
        }

        $roles = Role::all();
        $this->assign(compact('roles'));
        return $this->fetch();
    }

    public function update()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'username|用户名'=>'require|length:3,50|unique:admin,username,'.$data['id'].',id',
                'password|密码'=>'length:6,50',
                'avatar|头像'=>'file',
                'role_id|角色ID'=>'require|integer|notIn:0',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断用户是否存在
            $admin = AdminModel::where('id', $data['id'])->find();
            // 用来删除头像,不能全赋值,$admin可能是个单例模式对象
            $origin = $admin['avatar'];
            if (!$admin) {
                $this->error('用户不存在');
            }

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

            // 判断角色是否存在
            $role = Role::where('id', $data['role_id'])->value('id');
            if (!$role) {
                $this->error('角色不存在');
            }

            try {
                Db::startTrans();

                $admin->username = $data['username'];
                if (!empty($data['password'])) {
                    $admin->password = sha1($data['password']);
                }
                if (!empty($data['avatar'])) {
                    $admin->avatar = $data['avatar'];
                }
                if (!$admin->save()) {
                    throw new \Exception('修改失败');
                }

                $adminRole = AdminRole::where('admin_id', $data['id'])->find();
                $adminRole = $adminRole->save([
                    'role_id'=>$data['role_id'],
                ]);
                if (!$adminRole) {
                    throw new \Exception('修改失败');
                }

                // 判断头像是否被更换
                if ($admin['avatar'] != $origin) {
                    @unlink($_SERVER['DOCUMENT_ROOT'].$origin);
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                if (!empty($data['avatar'])) {
                    unset($info);
                    @unlink($_SERVER['DOCUMENT_ROOT'].$data['avatar']);
                }
                $this->error($e->getMessage());
            }
            $this->success('修改成功', 'Admin/index');
        }

        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        $info = AdminModel::with(['adminRole'])->where('id', $data['id'])->find();
        if (!$info) {
            $this->error('用户不存在');
        }

        $roles = Role::all();
        $this->assign(compact('info', 'roles'));
        return $this->fetch();
    }

    public function delete()
    {
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer|notIn:1',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        try {
            Db::startTrans();

            $admin = AdminModel::where('id', $data['id'])->find();
            $origin = $admin['avatar']; //不能把$admin整个赋值给$origin,$admin可以是个单例模式对象
            if (!$admin->delete()) {
                throw new \Exception('删除失败');
            }
            $adminRole = AdminRole::where('admin_id', $data['id'])->delete();
            if (!$adminRole) {
                throw new \Exception('删除失败');
            }

            // 判断是否有头像
            if (!empty($origin)) {
                @unlink($_SERVER['DOCUMENT_ROOT'].$origin);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success('删除成功', 'Admin/index');
    }
}
