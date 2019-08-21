<?php

namespace app\admin\controller;

use app\admin\model\Category as CategoryModel;
use app\common\controller\Backend;
use think\paginator\driver\Bootstrap;

class Category extends Backend
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
            $category = new CategoryModel;
            if ($category->saveAll($newData)) {
                $this->success('更新成功', 'Category/index');
            } else {
                $this->error('更新失败');
            }
        }

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
        $list = sort_two_array(json_decode(json_encode(CategoryModel::order('sort', 'asc')->all()), true));
        $data = array_slice($list, $offset, $list_rows, true);

        // 分页
        $list = Bootstrap::make($data, $list_rows, $page, count($list), false, [
            'var_page'=>'page',
            'path'=>url('Category/index'),
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
                'parent_id|上级栏目'=>'require|integer|token',
                'name|名称'=>'require|length:1,50',
                'type|类型'=>'require|integer',
                'link|链接'=>'max:255',
                'keywords|关键字'=>'max:255',
                'description|描述'=>'max:255',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断上级栏目是否存在
            if ($data['parent_id']) {
                $category = CategoryModel::where('id', $data['parent_id'])->value('id');
                if (!$category) {
                    $this->error('上级栏目不存在');
                }
            }

            $category = new CategoryModel;
            $category->name = $data['name'];
            $category->type = $data['type'];
            $category->link = $this->request->param('link');
            $category->keywords = $this->request->param('keywords');
            $category->description = $this->request->param('description');
            $category->content = $this->request->param('content');
            $category->sort = $data['sort'];
            $category->parent_id = $data['parent_id'];
            if ($category->save()) {
                $this->success('添加成功', 'Category/index');
            } else {
                $this->error('添加失败');
            }
        }

        $categorys = sort_two_array(json_decode(json_encode(CategoryModel::order('sort', 'asc')->all()), true));
        $this->assign(compact('categorys'));
        return $this->fetch();
    }

    public function update()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'parent_id|上级栏目'=>'require|integer|notIn:'.$this->request->param('id'),
                'name|名称'=>'require|length:1,50',
                'type|类型'=>'require|integer',
                'link|链接'=>'max:255',
                'keywords|关键字'=>'max:255',
                'description|描述'=>'max:255',
                'sort|排序'=>'require|integer',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断上级栏目是否存在
            if ($data['parent_id']) {
                $category = CategoryModel::where('id', $data['parent_id'])->value('id');
                if (!$category) {
                    $this->error('上级栏目不存在');
                }
            }
            // 获取原上级栏目,防止更新后的上级栏目是子栏目
            $parentCategoryIdNotIn = sort_two_array(json_decode(json_encode(CategoryModel::order('sort', 'asc')->all()), true), $data['id']);
            $parentCategoryIdNotIn = array_column($parentCategoryIdNotIn, 'id');
            if (in_array($data['parent_id'], $parentCategoryIdNotIn)) {
                $this->error('上级栏目不能是子栏目');
            }

            // 判断栏目是否存在
            $category = CategoryModel::get($data['id']);
            if (!$category) {
                $this->error('栏目不存在');
            }

            $category->name = $data['name'];
            $category->type = $data['type'];
            $category->link = $this->request->param('link');
            $category->keywords = $this->request->param('keywords');
            $category->description = $this->request->param('description');
            $category->content = $this->request->param('content');
            $category->sort = $data['sort'];
            $category->parent_id = $data['parent_id'];
            if ($category->save()) {
                $this->success('修改成功', 'Category/index');
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

        $info = CategoryModel::get($data['id']);
        if (!$info) {
            $this->error('栏目不存在');
        }

        sort_two_array([], 0, 0 , true);
        $categorys = sort_two_array(json_decode(json_encode(CategoryModel::order('sort', 'asc')->all()), true));

        if (isset($parentCategoryIdNotIn)) {
            $parentCategoryIdNotIn = array_merge($parentCategoryIdNotIn, [$data['id']]);
        } else {
            sort_two_array([], 0, 0 , true);
            $parentCategoryIdNotIn = sort_two_array($categorys, $data['id']);
            $parentCategoryIdNotIn = array_merge(array_column($parentCategoryIdNotIn, 'id'), [$data['id']]);
        }

        $this->assign(compact('info', 'categorys', 'parentCategoryIdNotIn'));
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

        $category = CategoryModel::with(['category', 'article'])->get($data['id']);
        if (!empty($category->category)) {
            $this->error('子栏目['.$category->category->name.']使用中,不能删除');
        }
        if (!empty($category->article)) {
            $this->error('文章['.$category->article->title.']使用中,不能删除');
        }

        $category = CategoryModel::destroy($data['id']);
        if ($category) {
            $this->success('删除成功', 'Category/index');
        } else {
            $this->error('删除失败');
        }
    }
}
