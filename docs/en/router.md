# Router

The routing configuration file is `app/Admin/routes.php`:

```php
<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
```

`$router` is the instance object of the`Encore\Admin\Routing\Router` class and is used in the same way as `Illuminate\Routing\Router`.

`$router` add prefix to all controllers which configured in `config/admin.php`. `$router` also adds namespaces to all configured controllers, such as the above` HomeController@index`. The `GET` request for url `http://localhost/admin/ `will be handled by `index` method of controller `App\Admin\Controllers\HomeController`.
