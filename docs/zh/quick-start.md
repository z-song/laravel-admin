# 快速开始

## 安装

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

## 默认配置

安装完成之后，`laravel-admin`所有的配置都在`config/admin.php`文件中。

## 目录结构
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

## 快速开始

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
