<?php

namespace app\admin\controller;

use app\admin\model\Config as ConfigModel;
use app\common\controller\Backend;

class Config extends Backend
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
            $config = new ConfigModel;
            if ($config->saveAll($newData)) {
                $this->success('更新成功', 'Config/index');
            } else {
                $this->error('更新失败');
            }
        }
        $list = ConfigModel::order('sort', 'asc')->paginate();
        $this->assign(compact('list'));
        return $this->fetch();
    }

    public function create()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'title|标题'=>'require|length:1,255|token',
                'name|变量名'=>'require|length:1,50|alpha|unique:config',
                'type|类型'=>'require|integer',
                'value|配置值'=>'length:1,255',
                'values|值'=>'length:1,255',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            $config = new ConfigModel;
            $config->title = $data['title'];
            $config->name = $data['name'];
            $config->type = $data['type'];
            if (!empty($data['value'])) {
                $config->value = $data['value'];
            }
            if (!empty($data['values'])) {
                $config->values = $data['values'];
            }
            $config->sort = $data['sort'];
            if ($config->save()) {
                $this->success('添加成功', 'Config/index');
            } else {
                $this->error('添加失败');
            }
        }
        return $this->fetch();
    }

    public function update()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'title|标题'=>'require|length:1,255',
                'name|变量名'=>'require|length:1,50|alpha|unique:config,name,'.$data['id'].',id',
                'type|类型'=>'require|integer',
                'value|配置值'=>'length:1,255',
                'values|值'=>'length:1,255',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            $config = ConfigModel::get($data['id']);
            if (!$config) {
                $this->error('配置不存在');
            }
            $config->title = $data['title'];
            $config->name = $data['name'];
            $config->type = $data['type'];
            if (!empty($data['value'])) {
                $config->value = $data['value'];
            }
            if (!empty($data['values'])) {
                $config->values = $data['values'];
            }
            $config->sort = $data['sort'];
            if ($config->save()) {
                $this->success('修改成功', 'Config/index');
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

        $info = ConfigModel::get($data['id']);
        if (!$info) {
            $this->error('配置不存在');
        }
        $this->assign(compact('info'));
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

        $config = ConfigModel::destroy($data['id']);
        if ($config) {
            $this->success('删除成功', 'Config/index');
        } else {
            $this->error('删除失败');
        }
    }

    public function save()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'value|值'=>'require|array|token',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 数据重组
            $newData = [];
            foreach ($data['value'] as $key=>$value) {
                if ((is_numeric($key) && strpos('.', $key) === false)) {
                    $newData[] = is_array($value) ? ['id'=>$key, 'values'=>implode(',', $value)] : ['id'=>$key, 'values'=>$value];
                }
            }

            // 更新数据
            $config = new ConfigModel;
            if ($config->saveAll($newData)) {
                $this->success('更新成功', 'Config/save');
            } else {
                $this->error('更新失败');
            }
        }
        $list = ConfigModel::order('sort', 'asc')->all();
        $this->assign(compact('list'));
        return $this->fetch();
    }
}
