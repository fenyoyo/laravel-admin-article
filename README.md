## 第一次拉取项目
需要执行以下步骤
```bash
# 安装项目依赖
composer install

# 复制环境变量文件，修改 .env文件中的数据库环境变量
cp .env.example .env
 
# 生成项目key
php artisan key:generate
# 生成软连接
php artisan storage:link

# 发布admin文件
php artisan admin:publish

# 生成数据
php artisan admin:install
php artisan db:seed

php artisan assets
# 启动项目
php artisan serve

#安装npm依赖
npm install

# 启动npm
npm run dev

```


# 重置数据库

```shell
#重置数据库迁移
php artisan migrate:refresh
#安装admin相关的数据 
php artisan admin:install
#安装应用的数据
php artisan db:seed
#清空缓存数据
php artisan cache:clear

```

```shell
php artisan test:reset
```

```shell
php artisan serve
```
