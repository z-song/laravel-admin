laravel-admin
=====

[![Build Status](https://travis-ci.org/z-song/laravel-admin.svg?branch=master)](https://travis-ci.org/z-song/laravel-admin)
[![StyleCI](https://styleci.io/repos/48796179/shield)](https://styleci.io/repos/48796179)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?maxAge=2592000)]()

`laravel-admin` 是一个可以快速帮你构建后台管理的工具，它提供的页面组件和表单元素等功能，能帮助你使用很少的代码就实现功能完善的后台管理功能。

[Demo](http://120.26.143.106/admin) 账号/密码:admin/admin

截图
------------

![grid](https://cloud.githubusercontent.com/assets/1479100/16609399/894e0832-4386-11e6-8709-1cc7ce429e7c.png)

![form](https://cloud.githubusercontent.com/assets/1479100/12708198/fc6725a8-c8d7-11e5-876f-5c4f00ded0ff.png)

安装
------------

```
Laravel 5.2
composer require encore/laravel-admin "dev-master"

Laravel 5.3
composer require encore/laravel-admin "1.3.x-dev"

Laravel 5.1
composer require encore/laravel-admin "1.1.x-dev"
```

在`config/app.php`加入`ServiceProvider`:

```
Encore\Admin\Providers\AdminServiceProvider::class
```

然后运行下面的命令完成安装：

```
php artisan vendor:publish --tag=laravel-admin
php artisan admin:install
```

启动服务后，在浏览器打开 `http://localhost/admin/` ,使用用户名 `admin` 和密码 `admin`登陆.

使用文档
------------

- [快速开始](/docs/zh/quick-start.md)
- [路由配置](/docs/zh/router.md)
- [菜单配置](/docs/zh/menu.md)
- [显示布局](/docs/zh/layout.md)
- [数据模型表格](/docs/zh/model-grid.md)
- [数据模型表单](/docs/zh/model-form.md)
- [组件](/docs/zh/table.md)
  - [表格](/docs/zh/table.md)
  - [表单](/docs/zh/form.md)
  - [盒子](/docs/zh/box.md)
  - [信息盒子](/docs/zh/info-box.md)
  - [选项卡](/docs/zh/box.md)
  - [滑动相册](/docs/zh/carousel.md)
  - [折叠容器](/docs/zh/collapse.md)
  - 数据图表 TODO
- [权限控制](/docs/zh/permission.md)

目录结构
------------
安装完成之后，后台的安装目录为`app/Admin`，之后大部分的后台开发编码工作都是在这个目录下进行。

`app/Admin/routes.php`文件用来配置后台路由，详细使用请阅读[路由配置](/docs/zh/router.md)。

`app/Admin/menu.php`文件用来配置后台左侧菜单栏，详细使用请阅读[菜单栏配置](/docs/zh/menu.md)。


`app/Admin/Controllers`目录用来存放后台路由器文件，该目录下的`HomeController.php`文件是后台首页的显示控制器，`ExampleController.php`为实例文件。

快速开始
------------

用`Laravel`自带的`users`表举例,表结构为：
```sql
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```
对应的数据模型为文件 `App\User.php`

`laravel-admin`可以通过使用以下几步来快速生成`users`表的`CURD`操作页面：

### 1.添加路由器
```php
<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class UserController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('用户名');
            $grid->email('邮箱');

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->display('id', 'ID');
            
            $form->text('name', '用户名');
            $form->email('email', '用户邮箱');
            $form->password('password', '密码');
            
            $form->dateTime('created_at', 'Created At');
            $form->dateTime('updated_at', 'Updated At');
        });
    }
}

```

### 2.添加路由配置

在`laravel-admin`的路由配置文件`app/Admin/routes.php`里添加一行：
```
$router->resource('users', UserController::class);
```

### 3.添加左侧菜单栏连接

打开文件`app/Admin/menu.php`,添加以下数据：

```
...
[
    'title' => '用户列表',
    'url'   => 'users',
    'icon'  => 'fa-users',
],
...

```

然后就能在后台管理页面的左侧边栏看到用户管理页面的链接入口了。

对于数据表格(model-grid)和数据表单(model-form)的详细使用请查看[model-grid]()和[model-form]()。

其它
------------
`laravel-admin` 基于以下组件或者服务:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Bootstrap Markdown](http://toopay.github.io/bootstrap-markdown/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [CodeMirror](https://codemirror.net/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

License
------------
`laravel-admin` is licensed under [The MIT License (MIT)](LICENSE).
