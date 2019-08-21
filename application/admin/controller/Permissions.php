<?php

namespace app\admin\controller;

use app\admin\model\Permissions as PermissionsModel;
use app\admin\model\RolePermissions;
use app\common\controller\Backend;
use think\paginator\driver\Bootstrap;

/*
 * 权限管理
 */
class Permissions extends Backend
{
    /**
     * 权限列表
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isPost()) {
            // 数据验证
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'sort|排序'=>'require|array|token',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 排序数据重组
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

        // 数据验证
        $data = $this->request->param();
        $validate = $this->validate($data, [
            'page|页码'=>'integer|notIn:0',
            'list_rows|每页条数'=>'integer|notIn:0',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }

        // 分页参数
        $page = abs($this->request->param('page', 1));
        $list_rows = abs($this->request->param('list_rows', 15));
        $offset = ($page - 1) * $list_rows;

        // 数据和分页数据
        $list = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        $data = array_slice($list, $offset, $list_rows, true);

        // 分页
        $list = Bootstrap::make($data, $list_rows, $page, count($list), false, [
            'var_page'=>'page',
            'path'=>url('Permissions/index'),
            'query'=>[],
            'fragment'=>'',
        ]);
        $list->appends($_GET);

        $this->assign(compact('list'));
        return $this->fetch();
    }

    /**
     * 权限添加
     * 该功能针对开发人员,项目完成后需要关闭该功能
     * @return mixed
     */
    public function create()
    {
        if ($this->request->isPost()) {
            // 数据验证
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

            // 数据入库
            $permissions = new PermissionsModel;
            $permissions->title = $data['title'];
            $permissions->icon = $this->request->param('icon');
            $permissions->path = $this->request->param('path');
            $permissions->menu = $data['menu'];
            $permissions->sort = $data['sort'];
            $permissions->parent_id = $data['parent_id'];
            if ($permissions->save()) {
                $this->success('添加成功', 'Permissions/index');
            } else {
                $this->error('添加失败');
            }
        }

        // 所有权限
        $permissions = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        $this->assign(compact('permissions'));
        return $this->fetch();
    }

    /**
     * 权限编辑
     * 该功能针对开发人员,项目完成后需要关闭该功能
     * @return mixed
     */
    public function update()
    {
        if ($this->request->isPost()) {
            // 数据验证
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'parent_id|上级权限'=>'require|integer|notIn:'.$this->request->param('id'),
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
            // 获取原上级权限,防止更新后的上级权限是子权限
            $parentPermissionsIdNotIn = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true), $data['id']);
            $parentPermissionsIdNotIn = array_column($parentPermissionsIdNotIn, 'id');
            if (in_array($data['parent_id'], $parentPermissionsIdNotIn)) {
                $this->error('上级权限不能是子权限');
            }
            // 判断权限是否存在
            $permissions = PermissionsModel::get($data['id']);
            if (!$permissions) {
                $this->error('权限不存在');
            }

            // 数据入库
            $permissions->title = $data['title'];
            $permissions->icon = $this->request->param('icon');
            $permissions->path = $this->request->param('path');
            $permissions->menu = $data['menu'];
            $permissions->sort = $data['sort'];
            $permissions->parent_id = $data['parent_id'];
            if ($permissions->save()) {
                $this->success('修改成功', 'Permissions/index');
            } else {
                $this->error('修改失败');
            }
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
        $info = PermissionsModel::get($data['id']);
        if (!$info) {
            $this->error('权限不存在');
        }
        // 所有权限,由于无限极排序函数的数据是在静态区,防止数据残留
        sort_two_array([], 0, 0 , true);
        $permissions = sort_two_array(json_decode(json_encode(PermissionsModel::order('sort', 'asc')->all()), true));
        // 获取自己ID和子权限ID,上级权限不能是自己和子权限ID
        if (isset($parentPermissionsIdNotIn)) {
            $parentPermissionsIdNotIn = array_merge($parentPermissionsIdNotIn, [$data['id']]);
        } else {
            // 由于无限极排序函数的数据是在静态区,防止数据残留
            sort_two_array([], 0, 0 , true);
            $parentPermissionsIdNotIn = sort_two_array($permissions, $data['id']);
            $parentPermissionsIdNotIn = array_merge(array_column($parentPermissionsIdNotIn, 'id'), [$data['id']]);
        }
        $this->assign(compact('info', 'permissions', 'parentPermissionsIdNotIn'));
        return $this->fetch();
    }

    /**
     * 权限删除
     * 该功能针对开发人员,项目完成后需要关闭该功能
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
        // 判断是否有子权限使用
        $permissions = PermissionsModel::with(['permissions'])->get($data['id']);
        if (!empty($permissions->permissions)) {
            $this->error('子权限['.$permissions->permissions->title.']使用中,不能删除');
        }
        // 判断是否有角色使用
        $rolePermissions = RolePermissions::with(['role'])->where('permissions_id', $data['id'])->find();
        if ($rolePermissions) {
            $this->error('角色['.$rolePermissions->role->name.']使用中,不能删除');
        }

        // 权限删除
        $permissions = PermissionsModel::destroy($data['id']);
        if ($permissions) {
            $this->success('删除成功', 'Permissions/index');
        } else {
            $this->error('删除失败');
        }
    }
}
