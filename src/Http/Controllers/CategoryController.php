<?php

namespace Intop\Article\Http\Controllers;

use App\Admin\Fun\EditorOptions;
use App\Admin\Repositories\Category;
use App\Models\Article;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Cache;

class CategoryController extends AdminController
{
    use EditorOptions;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new Category(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title')->tree();
            $grid->column('desc');
            $grid->column('cover')->image(width: 50, height: 50);
            $grid->column('icon');
            $grid->order()->orderable();
            $grid->column('article', '查看文章')->display(function () {
                $url = admin_route('article.index', ['category_id' => $this->id]);
                return '<a href="' . $url . '">查看文章</a>';
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('title');
            });
            $grid->quickSearch(['id', 'title']);
            $grid->showViewButton(false);

        });
    }

    /**
     * Make a show builder.
     *
     * @param $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new Category(), function (Show $show) {
            $show->field('id');
            $show->field('parent_id');
            $show->field('title');
            $show->field('desc');
            $show->field('cover');
            $show->field('icon');
            $show->field('content');
            $show->field('fields');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new Category(), function (Form $form) {
            $form->display('id');
            $form->select('parent_id')->options(function () {
                return \App\Models\Category::selectOptions();
            })->saving(function ($v) {
                return (int)$v;
            });
            $form->text('title')->required();
            $form->text('desc')->default('');
            $form->image('cover')->autoUpload()->uniqueName();
            $form->image('icon')->autoUpload()->uniqueName();
            $form->switch('is_menu');
            $form->text('url');
            $form->editor('content')->options($this->editorOptions());
            $form->table('fields', function (Form\NestedForm $table) {
                $table->text('key', '字段KEY')->placeholder('字段KEY');
                $type = [
                    'text' => '文本',
                    'list' => '列表',
//                    'file' => '文件',
//                    'image' => '图片'
                ];
                $table->select('type', '字段类型')->placeholder('字段类型')->options($type);
                $table->text('label', '字段标题')->placeholder('字段标题');
                $table->text('comment', '字段描述')->placeholder('字段描述');
            });
            $form->number('order');
            $form->display('created_at');
            $form->display('updated_at');
            $form->deleting(function (Form $form) {
                foreach ($form->model()->toArray() as $item) {
                    $exist = \App\Models\Category::query()->where('parent_id', $item['id'])->exists();
                    if ($exist) {
                        return $form->response()->error('栏目下有子分类，请先删除子分类！');
                    }
                    $exist = Article::query()->where('category_id', $item['id'])->exists();
                    if ($exist) {
                        return $form->response()->error('栏目下还存在文章，请先删除栏目下的文章！');
                    }
                }
//                return $form->response()->success('删除成功');
            });
            $form->saved(function (Form $form) {
                Cache::forget('menu');
            });
        });
    }
}
