<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{

    // 这里可以指定你的数据库连接
    public function getConnection()
    {
        return config('database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->getConnection())->create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('title')->default('')->comment('分类名称');
            $table->string('desc')->nullable()->default('')->comment('分类描述');
            $table->string('cover')->nullable()->default('')->comment('分类封面');
            $table->integer('order')->default(0)->comment('排序');
            $table->timestamps();
        });

        Schema::connection($this->getConnection())->create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('')->comment('文章标题');
            $table->string('summary')->nullable()->default('')->comment('文章摘要');
            $table->string('author')->nullable()->default('')->comment('作者');
            $table->integer('category_id')->default(0)->comment('分类ID');
            $table->string('cover')->nullable()->default('')->comment('封面');
            $table->enum('type', ['article', 'url', 'url_out', 'attachment'])->default('article')->comment('文章类型|article=普通文章,url=内部连接,url_out=外部连接,attachment=打开附件');
            $table->mediumText('content')->nullable()->comment('内容');
            $table->string('url')->nullable()->default('')->comment('内部连接');
            $table->string('attachment')->nullable()->default('')->comment('附件');
            $table->integer('order')->default(0)->comment('排序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->getConnection())->dropIfExists('categories');
        Schema::connection($this->getConnection())->dropIfExists('articles');
    }
}

;
