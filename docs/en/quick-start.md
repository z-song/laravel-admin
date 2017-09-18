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

#### 3.build grid and form

The rest needs to be done is open `app/Admin/Contollers/UserController.php`, find `form()` and `grid()` method and write few lines of code with `model-grid` and `model-form`，for more detail, please read [model-grid](/docs/en/model-grid.md) and [model-form](/docs/en/model-form.md).