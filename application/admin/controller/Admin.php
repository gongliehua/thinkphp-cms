<?php

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use app\admin\model\Role;
use app\admin\model\AdminRole;
use app\common\controller\Backend;
use think\Db;

/*
 * 用户管理
 */
class Admin extends Backend
{
    /**
     * 用户列表
     * @return mixed
     */
	public function index()
	{
	    $list = AdminModel::with(['adminRole'=>['role']])->paginate();
	    $this->assign(compact('list'));
	    return $this->fetch();
    }

    /**
     * 用户添加
     * @return mixed
     */
    public function create()
    {
        if ($this->request->isPost()) {
            // 数据验证
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
            // 判断是否传了头像
            if (!empty($_FILES['avatar']['name'])) {
                $file = request()->file('avatar');
                $info = $file->validate(['ext'=>'bmp,jpg,jpeg,png,gif'])->move( './uploads/avatar');
                if($info){
                    $data['avatar'] = '/uploads/avatar/'.$info->getSaveName();
                    $data['avatar'] = str_replace('\\', '/', $data['avatar']);
                    unset($info);
                }else{
                    $this->error($file->getError());
                }
            }
            // 判断角色是否存在
            $role = Role::where('id', $data['role_id'])->value('id');
            if (!$role) {
                $this->error('角色不存在');
            }

            try {
                Db::startTrans();

                // 用户信息入库
                $admin = new AdminModel;
                $admin->username = $data['username'];
                $admin->password = sha1($data['password']);
                if (!empty($data['avatar'])) {
                    $admin->avatar = $data['avatar'];
                }
                if (!$admin->save()) {
                    throw new \Exception('添加失败');
                }

                // 用户角色关联添加
                $adminRole = new AdminRole;
                $adminRole->admin_id = $admin->id;
                $adminRole->role_id = $data['role_id'];
                if (!$adminRole->save()) {
                    throw new \Exception('添加失败');
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                if (!empty($data['avatar'])) {
                    @unlink($_SERVER['DOCUMENT_ROOT'].$data['avatar']);
                }
                $this->error($e->getMessage());
            }
            $this->success('添加成功', 'Admin/index');
        }

        // 所有角色
        $roles = Role::all();
        $this->assign(compact('roles'));
        return $this->fetch();
    }

    /**
     * 用户更新
     * @return mixed
     */
    public function update()
    {
        if ($this->request->isPost()) {
            // 数据验证
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'username|用户名'=>'require|length:3,50|unique:admin,username,'.$this->request->param('id').',id',
                'password|密码'=>'length:6,50',
                'avatar|头像'=>'file',
                'role_id|角色ID'=>'require|integer|notIn:0',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }
            // 判断用户是否存在
            $admin = AdminModel::where('id', $data['id'])->find();
            if (!$admin) {
                $this->error('用户不存在');
            }
            // 更新头像后用来删除原头像
            $avatar = $admin['avatar'];
            // 判断文件是否存在
            if (!empty($_FILES['avatar']['name'])) {
                $file = request()->file('avatar');
                $info = $file->validate(['ext'=>'bmp,jpg,jpeg,png,gif'])->move( './uploads/avatar');
                if($info){
                    $data['avatar'] = '/uploads/avatar/'.$info->getSaveName();
                    $data['avatar'] = str_replace('\\', '/', $data['avatar']);
                    unset($info);
                }else{
                    $this->error($file->getError());
                }
            }
            // 判断角色是否存在
            $role = Role::where('id', $data['role_id'])->value('id');
            if (!$role) {
                $this->error('角色不存在');
            }

            try {
                Db::startTrans();

                // 用户信息修改
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

                // 用户角色关联修改
                $adminRole = AdminRole::where('admin_id', $data['id'])->find();
                $adminRole->role_id = $data['role_id'];
                if (!$adminRole->save()) {
                    throw new \Exception('修改失败');
                }

                // 判断头像是否被更新
                if ($admin['avatar'] != $avatar) {
                    @unlink($_SERVER['DOCUMENT_ROOT'].$avatar);
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                if (!empty($data['avatar'])) {
                    @unlink($_SERVER['DOCUMENT_ROOT'].$data['avatar']);
                }
                $this->error($e->getMessage());
            }
            $this->success('修改成功', 'Admin/index');
        }

        // 数据验证
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        // 基本信息
        $info = AdminModel::with(['adminRole'])->where('id', $data['id'])->find();
        if (!$info) {
            $this->error('用户不存在');
        }
        // 所有角色
        $roles = Role::all();
        $this->assign(compact('info', 'roles'));
        return $this->fetch();
    }

    /**
     * 用户删除
     */
    public function delete()
    {
        // 数据验证
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer|notIn:1',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        try {
            Db::startTrans();

            // 用户删除
            $admin = AdminModel::where('id', $data['id'])->find();
            $avatar = $admin['avatar']; //用来删除头像
            if (!$admin->delete()) {
                throw new \Exception('删除失败');
            }

            // 用户角色关联删除
            $adminRole = AdminRole::where('admin_id', $data['id'])->delete();
            if (!$adminRole) {
                throw new \Exception('删除失败');
            }

            // 删除头像文件
            if (!empty($avatar)) {
                @unlink($_SERVER['DOCUMENT_ROOT'].$avatar);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success('删除成功', 'Admin/index');
    }
}
