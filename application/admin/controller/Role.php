<?php

namespace app\admin\controller;

use app\admin\model\Role as RoleModel;
use app\admin\model\AdminRole;
use app\admin\model\Permissions;
use app\admin\model\RolePermissions;
use app\common\controller\Backend;
use think\Db;

/*
 * 角色管理
 */
class Role extends Backend
{
    /**
     * 角色列表
     * @return mixed
     */
    public function index()
    {
        $list = RoleModel::paginate();
        $this->assign(compact('list'));
        return $this->fetch();
    }

    /**
     * 角色添加
     * @return mixed
     */
    public function create()
    {
        if ($this->request->isPost()) {
            // 验证数据
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'name|名称'=>'require|length:1,50|token',
                'status|状态'=>'require|integer',
                'permissions_id|权限'=>'array',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            try {
                Db::startTrans();

                // 角色添加
                $role = new RoleModel;
                $role->name = $data['name'];
                $role->status = $data['status'];
                if (!$role->save()) {
                    throw new \Exception('添加失败');
                }

                // 判断是否拥有权限
                $permissions = [];
                if (!empty($data['permissions_id'])) {
                    // 根据数据库获取权限ID
                    $data['permissions_id'] = Permissions::where('id', 'in', $data['permissions_id'])->column('id');
                    // 重组数据
                    foreach ($data['permissions_id'] as $value) {
                        $permissions[] = ['role_id'=>$role->id, 'permissions_id'=>$value];
                    }
                    // 角色权限关联添加
                    if ($permissions) {
                        $rolePermissions = new RolePermissions;
                        if (!$rolePermissions->saveAll($permissions)) {
                            throw new \Exception('添加失败');
                        }
                    }
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success('添加成功', 'Role/index');
        }

        // 所有权限
        $permissions = sort_two_array(json_decode(json_encode(Permissions::order('sort', 'asc')->all()), true));
        $this->assign(compact('permissions'));
        return $this->fetch();
    }

    /**
     * 角色编辑
     * @return mixed
     * @throws \Exception
     */
    public function update()
    {
        if ($this->request->isPost()) {
            // 数据验证
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'name|名称'=>'require|length:1,50',
                'status|状态'=>'require|integer',
                'permissions_id|权限'=>'array',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            try {
                Db::startTrans();

                // 判断角色是否存在
                $role = RoleModel::get($data['id']);
                if (!$role) {
                    throw new \Exception('角色不存在');
                }

                // 角色修改
                $role->name = $data['name'];
                $role->status = $data['status'];
                if (!$role->save()) {
                    throw new \Exception('修改失败');
                }

                // 删除原拥有的权限
                $permissions = RolePermissions::where('role_id', $data['id'])->value('id');
                if ($permissions) {
                    $permissions = RolePermissions::where('role_id', $data['id'])->delete();
                    if (!$permissions) {
                        throw new \Exception('修改失败');
                    }
                }

                // 判断是否拥有权限
                $permissions = [];
                if (!empty($data['permissions_id'])) {
                    // 根据数据库获取权限ID
                    $data['permissions_id'] = Permissions::where('id', 'in', $data['permissions_id'])->column('id');
                    // 重组数据
                    foreach ($data['permissions_id'] as $value) {
                        $permissions[] = ['role_id'=>$role->id, 'permissions_id'=>$value];
                    }
                    // 角色权限关联添加
                    if ($permissions) {
                        $rolePermissions = new RolePermissions;
                        if (!$rolePermissions->saveAll($permissions)) {
                            throw new \Exception('修改失败');
                        }
                    }
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success('修改成功', 'Role/index');
        }

        // 数据验证
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        // 判断角色是否存在
        $info = RoleModel::get($data['id']);
        if (!$info) {
            throw new \Exception('角色不存在');
        }
        $info['permissions_id'] = RolePermissions::where('role_id', $data['id'])->column('permissions_id');

        // 权限
        $permissions = sort_two_array(json_decode(json_encode(Permissions::order('sort', 'asc')->all()), true));
        $this->assign(compact('info', 'permissions'));
        return $this->fetch();
    }

    /**
     * 角色删除
     */
    public function delete()
    {
        // 数据验证
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        try {
            Db::startTrans();

            // 判断角色是否存在
            $role = RoleModel::where('id', $data['id'])->value('id');
            if (!$role) {
                throw new \Exception('角色不存在');
            }

            // 判断角色是否有用户使用
            $adminRole = AdminRole::with(['admin'])->where('role_id', $data['id'])->find();
            if ($adminRole) {
                throw new \Exception('用户['.$adminRole->admin->username.']使用中,不能删除');
            }

            // 判断该角色是否拥有权限,有则删除
            $rolePermissions = RolePermissions::where('role_id', $data['id'])->value('id');
            if ($rolePermissions) {
                $rolePermissions = RolePermissions::where('role_id', $data['id'])->delete();
                if (!$rolePermissions) {
                    throw new \Exception('删除失败');
                }
            }

            // 删除角色
            $role = RoleModel::destroy($data['id']);
            if (!$role) {
                throw new \Exception('删除失败');
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success('删除成功');
    }
}
