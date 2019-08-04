<?php

namespace app\admin\controller;

use app\admin\model\Permissions as PermissionsModel;
use app\admin\model\RolePermissions;
use app\common\controller\Backend;
use think\paginator\driver\Bootstrap;

class Permissions extends Backend
{
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'sort|排序'=>'require|array|token',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 数据重组
            $newData = [];
            foreach ($data['sort'] as $key=>$value) {
                if ((is_numeric($key) && strpos('.', $key) === false) && is_numeric($value) && strpos('.', $value) === false) {
                    $newData[] = ['id'=>$key, 'sort'=>$value];
                }
            }

            // 更新排序
            $permissions = new PermissionsModel;
            if ($permissions->saveAll($newData)) {
                $this->success('更新成功', 'Permissions/index');
            } else {
                $this->error('更新失败');
            }
        }

        $data = $this->request->param();
        $validate = $this->validate($data, [
            'page|页码'=>'integer|notIn:0',
            'per_page|每页条数'=>'integer|notIn:0',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        // 分页参数
        $page = abs($this->request->param('page', 1));
        $per_page = abs($this->request->param('per_page', 15));
        $offset = ($page - 1) * $per_page;

        // 数据和分页数据
        $list = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        $data = array_slice($list, $offset, $per_page, true);

        // 分页
        $list = Bootstrap::make($data, $per_page, $page, count($list), false, [
            'var_page'=>'page',
            'path'=>url('Permissions/index'),
            'query'=>[],
            'fragment'=>'',
        ]);
        $list->appends($_GET);

        $this->assign(compact('list'));
        return $this->fetch();
    }

    public function create()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'parent_id|上级权限'=>'require|integer|token',
                'title|标题'=>'require|length:1,50',
                'menu|菜单'=>'require|integer',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断上级权限是否存在
            if ($data['parent_id']) {
                $permissions = PermissionsModel::where('id', $data['parent_id'])->value('id');
                if (!$permissions) {
                    $this->error('上级权限不存在');
                }
            }

            $permissions = new PermissionsModel;
            $permissions->title = $data['title'];
            if (!empty($data['icon'])) {
                $permissions->icon = $data['icon'];
            }
            if (!empty($data['path'])) {
                $permissions->path = $data['path'];
            }
            $permissions->menu = $data['menu'];
            $permissions->sort = $data['sort'];
            $permissions->parent_id = $data['parent_id'];
            if ($permissions->save()) {
                $this->success('添加成功', 'Permissions/index');
            } else {
                $this->error('添加失败');
            }
        }

        $permissions = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        $this->assign(compact('permissions'));
        return $this->fetch();
    }

    public function update()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'parent_id|上级权限'=>'require|integer|notIn:'.$data['id'],
                'title|标题'=>'require|length:1,50',
                'menu|菜单'=>'require|integer',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断上级权限是否存在
            if ($data['parent_id']) {
                $permissions = PermissionsModel::where('id', $data['parent_id'])->value('id');
                if (!$permissions) {
                    $this->error('上级权限不存在');
                }
            }

            // 判断权限是否存在
            $permissions = PermissionsModel::get($data['id']);
            if (!$permissions) {
                $this->error('权限不存在');
            }

            $permissions->title = $data['title'];
            if (!empty($data['icon'])) {
                $permissions->icon = $data['icon'];
            }
            if (!empty($data['path'])) {
                $permissions->path = $data['path'];
            }
            $permissions->menu = $data['menu'];
            $permissions->sort = $data['sort'];
            $permissions->parent_id = $data['parent_id'];
            if ($permissions->save()) {
                $this->success('修改成功', 'Permissions/index');
            } else {
                $this->error('修改失败');
            }
        }

        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        $info = PermissionsModel::get($data['id']);
        if (!$info) {
            $this->error('权限不存在');
        }

        $permissions = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        $this->assign(compact('info', 'permissions'));
        return $this->fetch();
    }

    public function delete()
    {
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'id|ID'=>'require|integer',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        $rolePermissions = RolePermissions::with(['role'])->where('permissions_id', $data['id'])->find();
        if ($rolePermissions) {
            $this->error('角色['.$rolePermissions->role->name.']使用中,不能删除');
        }

        $permissions = PermissionsModel::destroy($data['id']);
        if ($permissions) {
            $this->success('删除成功', 'Permissions/index');
        } else {
            $this->error('删除失败');
        }
    }
}
