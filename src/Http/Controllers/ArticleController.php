<?php

namespace Intop\Article\Http\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Support\JavaScript;
use Intop\Article\Enums\ArticleTypeEnum;
use Intop\Article\Models\Article;
use Intop\Article\Models\Category;

class ArticleController extends AdminController
{

    protected $title = '文章管理';
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new Article(), function (Grid $grid) {
            $grid->model()->with(['category']);
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('title', '标题')->limit(10);
            $grid->column('summary', '副标题')->limit(10);
            $grid->column('category.title', '分类名称')->filter();
            $grid->column('type', '文章类型')->using(ArticleTypeEnum::options())->filter(Grid\Column\Filter\In::make(ArticleTypeEnum::options()));
            $grid->column('order', '排序')->orderable();
            $grid->column('created_at', '创建时间');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();
                $filter->equal('id', 'ID')->width(2);
                $filter->like('title', '标题')->width(3);
            });
            $grid->showViewButton(false);
        });
    }

    protected function form(): Form
    {
        return Form::make(new Article, function (Form $form) {
            $form->display('id');
            $form->select('category_id', '分类')
                ->required()
                ->placeholder('请选择')->options(function () {
                    return Category::selectOptions();
                })->saving(function ($v) {
                    return (int)$v;
                });
            $form->text('title', '标题')->required();
            $form->text('summary', '副标题');
            $form->image('cover', '封面')->autoUpload()->uniqueName();
            $form->radio('type', '类型')->options(ArticleTypeEnum::options())
                ->default('article')
                ->when('article', function (Form $form) {
                    $form->editor('content', '文章内容')->options($this->editorOptions());
                })->when(['url', 'out_url'], function (Form $form) {
                    $form->text('url', '链接')->help('内部链接请输入相对路径,外部链接请输入绝对路径');
                })->when('attachment', function (Form $form) {
                    $form->file('attachment', '附件')->maxSize(300 * 1024)->chunked()->autoUpload();
                });
            $form->text('author', '作者');
            $form->number('order', '排序');
            $form->datetime('created_at', '创建时间')->default(now());
            $form->display('updated_at', '编辑时间');
        });
    }

    public function editorOptions(): array
    {


        return [
            "plugins" => ["advlist", "autolink", "link", "image", "media", "lists", "preview", "code", "help", "fullscreen", "table", "autoresize", "codesample", "indent2em"],
            'toolbar' => ["undo redo | preview fullscreen | styleselect | fontsizeselect bold italic underline strikethrough forecolor backcolor | link image media blockquote removeformat codesample", "alignleft aligncenter alignright  alignjustify|indent2em indent outdent bullist numlist table subscript superscript | code"],
            'file_picker_types' => 'file media',
            'file_upload_url' => admin_url('/dcat-api/tinymce/upload') . '?_token=' . csrf_token(),
            'file_picker_callback' => JavaScript::make(<<<JS
function file_upload(callback, value, meta){
    var filetype='.pdf, .txt, .zip, .rar, .7z, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .mp3, .mp4';
    //后端接收上传文件的地址
    var upurl = opts.file_upload_url+'&dir=tinymce%2F'+meta.filetype;
    //为不同插件指定文件类型及后端地址
    switch(meta.filetype){
        case 'image':
            filetype='.jpg, .jpeg, .png, .gif';
            break;
        case 'media':
            filetype='.mp4';
            break;
        case 'file':

        default:
    }
    //模拟出一个input用于添加本地文件
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', filetype);
    input.click();
    input.onchange = function() {
        var file = this.files[0];
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', upurl);
        xhr.onload = function() {
            var json;
            if (xhr.status !== 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            callback(json.location);
        };
        formData = new FormData();
        formData.append('file', file, file.name );
        xhr.send(formData);
    }
}
JS
            )
        ];
    }
}
