# 路由配置

路由配置文件为`app/Admin/routes.php`：

```php
<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
```

`$router`为`Encore\Admin\Routing\Router`类的实例对象，它的使用方法和`Illuminate\Routing\Router`是一样的。

`$router`已经给所有配置`url`加上了`prefix`，配置在`config/admin.php`的`prefix`配置项中。`$router`同时给所有配置的控制器加上了命名空间，比如上面的`HomeController@index`,对于`http://localhost/admin/`的`GET`请求,会被路由器`App\Admin\Controllers\HomeController`的`index`方法处理。
