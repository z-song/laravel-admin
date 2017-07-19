laravel-admin
=====

[![Build Status](https://travis-ci.org/z-song/laravel-admin.svg?branch=master)](https://travis-ci.org/z-song/laravel-admin)
[![StyleCI](https://styleci.io/repos/48796179/shield)](https://styleci.io/repos/48796179)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/z-song/laravel-admin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/z-song/laravel-admin/?branch=master)
[![Packagist](https://img.shields.io/packagist/l/encore/laravel-admin.svg?maxAge=2592000)](https://packagist.org/packages/encore/laravel-admin)
[![Total Downloads](https://img.shields.io/packagist/dt/encore/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/encore/laravel-admin)

`laravel-admin` is administrative interface builder for laravel which can help you build CRUD backends just with few lines of code.

[Demo](http://120.26.143.106/admin) use `username/password:admin/admin`

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

[中文文档](/docs/zh/README.md)

Screenshots
------------

![laravel-admin](https://cloud.githubusercontent.com/assets/1479100/19625297/3b3deb64-9947-11e6-807c-cffa999004be.jpg)

Installation
------------

First, install laravel, and make sure that the database connection settings are correct.

```
Laravel 5.2
composer require encore/laravel-admin "dev-master"

Laravel 5.3
composer require encore/laravel-admin "1.3.x-dev"

Laravel 5.1
composer require encore/laravel-admin "1.1.x-dev"
```


Then run these commands to finnish install：

```
$ php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
$ php artisan migrate
$ php artisan db:seed --class="Encore\Admin\Auth\Database\AdminTablesSeeder"
$ php artisan admin:install
```

open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

Default Settings
------------
The file in `config/admin.php` contains an array of settings, you can find the default settings in there.

Documentation
------------

- [quick start](/docs/en/quick-start.md)
- [router](/docs/en/router.md)
- [menu](/docs/en/menu.md)
- [layout](/docs/en/layout.md)
- [model-grid](/docs/en/model-grid.md)
- [model-form](/docs/en/model-form.md)
- [widgets](/docs/en/widgets/table.md)
  - [table](/docs/en/widgets/table.md)
  - [form](/docs/en/widgets/form.md)
  - [box](/docs/en/widgets/box.md)
  - [info-box](/docs/en/widgets/info-box.md)
  - [tab](/docs/en/widgets/box.md)
  - [carousel](/docs/en/widgets/carousel.md)
  - [collapse](/docs/en/widgets/collapse.md)
- [RBAC](/docs/en/permission.md)

Directory structure
------------
After install,you can find directory`app/Admin`,and then most of our develop work is under this directory.

```

app/Admin
├── Controllers
│   ├── ExampleController.php
│   └── HomeController.php
└── routes.php
```

`app/Admin/routes.php` is used to define routes，for more detail please read [routes](/docs/zh/router.md).

The `app/Admin/Controllers` directory  is used to store all the controllers, The `HomeController.php` file under this directory is used to handle home request of admin,The `ExampleController.php` file is a controller example.

Quick start
------------

We use `users` table come with `Laravel` for example,the structure of table is:
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
And the model for this table is `App\User.php`

You can follow these steps to setup `CURD` interfaces of table `users`:

#### 1.add controller

Use the following command to create a controller for `App\User` model

```php
php artisan admin:make UserController --model=App\\User
```
The above command will create the controller in `app/Admin/Controllers/UserController.php`.

#### 2.add route

Add a route in `app/Admin/routes.php`：
```
$router->resource('users', UserController::class);
```

#### 3.add left menu item

Open `http://localhost:8000/admin/auth/menu`, add menu link and refresh the page, then you can find a link item in left menu bar.

#### 4.build grid and form

The rest needs to be done is open `app/Admin/Contollers/UserController.php`, find `form()` and `grid()` method and write few lines of code with `model-grid` and `model-form`，for more detail, please read [model-grid](/docs/en/model-grid.md) and [model-form](/docs/en/model-form.md).

Other
------------
`laravel-admin` based on thses plugins or services:

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
+ [Nestable](http://dbushell.github.io/Nestable/)
+ [noty](http://ned.im/noty/)

License
------------
`laravel-admin` is licensed under [The MIT License (MIT)](LICENSE).
