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
