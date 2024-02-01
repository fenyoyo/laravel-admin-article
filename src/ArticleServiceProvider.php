<?php

namespace Intop\Article;

use Dcat\Admin\Extend\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{

    protected $menu = [
        [
            'title' => '文章管理',
            'uri' => 'article',
            'icon' => '', // 图标可以留空
        ],
        [
            'parent' => '文章管理',
            'title' => '分类列表',
            'uri' => 'category',
        ],
        [
            'parent' => '文章管理',
            'title' => '文章列表',
            'uri' => 'article',
        ],
    ];

}
