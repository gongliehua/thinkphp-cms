<?php

namespace app\admin\controller;

use app\admin\model\Article as ArticleModel;
use app\admin\model\Category;
use app\common\controller\Backend;

class Article extends Backend
{
    public function index()
    {
        $list = ArticleModel::with(['category'])->order('create_time', 'desc')->paginate();
        $this->assign(compact('list'));
        return $this->fetch();
    }

    public function create()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'category_id|所属栏目'=>'require|integer|token',
                'title|标题'=>'require|length:1,255',
                'keywords|关键字'=>'max:255',
                'description|描述'=>'max:255',
                'content|内容'=>'require',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断所属栏目是否存在
            $category = Category::where('id', $data['category_id'])->value('id');
            if (!$category) {
                $this->error('所属栏目不存在');
            }

            $article = new ArticleModel;
            $article->title = $data['title'];
            $article->keywords = $this->request->param('keywords');
            $article->description = $this->request->param('description');
            $article->content = $data['content'];
            $article->category_id = $data['category_id'];
            if ($article->save()) {
                $this->success('添加成功', 'Article/index');
            } else {
                $this->error('添加失败');
            }
        }

        $categorys = sort_two_array(json_decode(json_encode(Category::order('sort', 'asc')->all()), true));
        $this->assign(compact('categorys'));
        return $this->fetch();
    }

    public function update()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $validate = $this->validate($data, [
                'id|ID'=>'require|integer|token',
                'category_id|所属栏目'=>'require|integer',
                'title|标题'=>'require|length:1,255',
                'keywords|关键字'=>'max:255',
                'description|描述'=>'max:255',
                'content|内容'=>'require',
            ]);
            if ($validate !== true) {
                $this->error($validate);
            }

            // 判断所属栏目是否存在
            $category = Category::where('id', $data['category_id'])->value('id');
            if (!$category) {
                $this->error('所属栏目不存在');
            }

            // 判断文章是否存在
            $article = ArticleModel::get($data['id']);
            if (!$article) {
                $this->error('文章不存在');
            }

            $article->title = $data['title'];
            $article->keywords = $this->request->param('keywords');
            $article->description = $this->request->param('description');
            $article->content = $data['content'];
            $article->category_id = $data['category_id'];
            if ($article->save()) {
                $this->success('修改成功', 'Article/index');
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

        $info = ArticleModel::get($data['id']);
        if (!$info) {
            $this->error('文章不存在');
        }

        $categorys = sort_two_array(json_decode(json_encode(Category::order('sort', 'asc')->all()), true));
        $this->assign(compact('info', 'categorys'));
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

        $article = ArticleModel::destroy($data['id']);
        if ($article) {
            $this->success('删除成功', 'Article/index');
        } else {
            $this->error('删除失败');
        }
    }
}
