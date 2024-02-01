<?php

namespace Intop\Article\Http\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Intop\Article\Models\Article;
use Intop\Article\Models\Category;

class CategoryController extends AdminController
{

    protected $title = '分类管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new Category(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title', '标题')->tree();
            $grid->column('desc', '描述');
            $grid->column('cover', '封面')->image(width: 50, height: 50);
            $grid->column('order')->orderable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id', 'ID');
                $filter->equal('title', '标题');
            });
            $grid->quickSearch(['id', 'title']);
            $grid->showViewButton(false);

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
            $form->select('parent_id', '父级分类')->options(function () {
                return Category::selectOptions();
            })->saving(function ($v) {
                return (int)$v;
            });
            $form->text('title', '标题')->required();
            $form->text('desc', '描述')->default('');
            $form->image('cover', '封面')->autoUpload()->uniqueName();
            $form->number('order', '排序');
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '修改时间');
            $form->deleting(function (Form $form) {
                foreach ($form->model()->toArray() as $item) {
                    $exist = Category::query()->where('parent_id', $item['id'])->exists();
                    if ($exist) {
                        return $form->response()->error('栏目下有子分类，请先删除子分类！');
                    }
                    $exist = Article::query()->where('category_id', $item['id'])->exists();
                    if ($exist) {
                        return $form->response()->error('栏目下还存在文章，请先删除栏目下的文章！');
                    }
                }
            });
        });
    }


}
