laravel-admin
=====

[![Build Status](https://travis-ci.org/z-song/laravel-admin.svg?branch=master)](https://travis-ci.org/z-song/laravel-admin)
[![StyleCI](https://styleci.io/repos/48796179/shield)](https://styleci.io/repos/48796179)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/z-song/laravel-admin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/z-song/laravel-admin/?branch=master)
[![Packagist](https://img.shields.io/packagist/l/encore/laravel-admin.svg?maxAge=2592000)](https://packagist.org/packages/encore/laravel-admin)
[![Total Downloads](https://img.shields.io/packagist/dt/encore/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/encore/laravel-admin)

`laravel-admin` 是一个可以快速帮你构建后台管理的工具，它提供的页面组件和表单元素等功能，能帮助你使用很少的代码就实现功能完善的后台管理功能。

[Demo](http://120.26.143.106/admin) 账号/密码:admin/admin

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

截图
------------

![laravel-admin](https://cloud.githubusercontent.com/assets/1479100/19625297/3b3deb64-9947-11e6-807c-cffa999004be.jpg)

安装
------------

首先确保安装好了`laravel`，并且数据库连接设置正确。

```
Laravel 5.1
composer require encore/laravel-admin "1.1.*"

Laravel 5.2
composer require encore/laravel-admin "1.2.*"

Laravel 5.3
composer require encore/laravel-admin "1.3.*"
```

在`config/app.php`加入`ServiceProvider`:

```
Encore\Admin\Providers\AdminServiceProvider::class
```

然后运行下面的命令来发布资源：

```
php artisan vendor:publish --tag=laravel-admin
```

在该命令会生成配置文件`config/admin.php`，可以在里面修改安装的地址、数据库连接、以及表名。

然后运行下面的命令完成安装：
```
php artisan admin:install
```

启动服务后，在浏览器打开 `http://localhost/admin/` ,使用用户名 `admin` 和密码 `admin`登陆.

默认配置
------------

安装完成之后，`laravel-admin`所有的配置都在`config/admin.php`文件中。

使用文档
------------

- [快速开始](/docs/zh/quick-start.md)
- [路由配置](/docs/zh/router.md)
- [菜单配置](/docs/zh/menu.md)
- [显示布局](/docs/zh/layout.md)
- [数据模型表格](/docs/zh/model-grid.md)
  - [列操作和扩展](/docs/zh/model-grid-column.md)
- [数据模型表单](/docs/zh/model-form.md)
  - [图片/文件上传](/docs/zh/form-upload.md)
  - [form组件使用](/docs/zh/model-form-fields.md)
  - [form组件管理](/docs/zh/field-management.md)
- [组件](/docs/zh/widgets/table.md)
  - [表格](/docs/zh/widgets/table.md)
  - [表单](/docs/zh/widgets/form.md)
  - [盒子](/docs/zh/widgets/box.md)
  - [信息盒子](/docs/zh/widgets/info-box.md)
  - [选项卡](/docs/zh/widgets/tab.md)
  - [滑动相册](/docs/zh/widgets/carousel.md)
  - [折叠容器](/docs/zh/widgets/collapse.md)
  - 数据图表 TODO
- [权限控制](/docs/zh/permission.md)
- [常见问题](/docs/zh/qa.md)

目录结构
------------
安装完成之后，后台的安装目录为`app/Admin`，之后大部分的后台开发编码工作都是在这个目录下进行。

```
app/Admin
├── Controllers
│   ├── ExampleController.php
│   └── HomeController.php
├── bootstrap.php
└── routes.php
```

`app/Admin/routes.php`文件用来配置后台路由，详细使用请阅读[路由配置](/docs/zh/router.md)。

`app/Admin/bootstrap.php` 是`laravel-admin`的启动文件, 使用方法请参考文件里面的注释.

`app/Admin/Controllers`目录用来存放后台控制器文件，该目录下的`HomeController.php`文件是后台首页的显示控制器，`ExampleController.php`为实例文件。

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

使用下面的命令来创建一个对应`App\User`模型的路由器
```php
php artisan admin:make UserController --model=App\\User

// 在windows系统中
php artisan admin:make UserController --model=App\User
```

上面的命令会创建路由器文件`app/Admin/Controllers/UserController.php`.

### 2.添加路由配置

在`laravel-admin`的路由配置文件`app/Admin/routes.php`里添加一行：
```
$router->resource('users', UserController::class);
```

### 3.添加左侧菜单栏连接

打开`http://localhost:8000/admin/auth/menu`,添加对应的menu

然后就能在后台管理页面的左侧边栏看到用户管理页面的链接入口了。

### 4.创建表格表单

剩下的工作就是构建数据表格和表单了，打开 `app/Admin/Contollers/UserController.php`,找到`form()`和`grid()`方法，然添加构建代码,更多详细使用请查看[model-grid](/docs/zh/model-grid.md)和[model-form](/docs/zh/model-form.md)。

其它
------------
`laravel-admin` 基于以下组件或者服务:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [CodeMirror](https://codemirror.net/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)
+ [Nestable](http://dbushell.github.io/Nestable/)
+ [toastr](http://codeseven.github.io/toastr/)
+ [X-editable](http://github.com/vitalets/x-editable)
+ [bootstrap-number-input](https://github.com/wpic/bootstrap-number-input)
+ [fontawesome-iconpicker](https://github.com/itsjavi/fontawesome-iconpicker)

交流
------------
QQ群:278455482


License
------------
`laravel-admin` is licensed under [The MIT License (MIT)](LICENSE).
