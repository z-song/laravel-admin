# 常见问题汇总

## 关于自定义视图

如果有需要自己修改view，但是不方便直接修改`laravel-admin`的情况，可以用下面的办法解决

复制`vendor/encore/laravel-admin/views`到项目的`resources/views/admin`，然后在`app/Admin/bootstrap.php`文件中加入代码：

```php
app('view')->prependNamespace('admin', resource_path('views/admin'));
```

这样就用`resources/views/admin`下的视图覆盖了`laravel-admin`的视图，要注意的问题是，更新`laravel-admin`的时候，可能会遇到视图不存在的问题，这个需要自行解决

## 设置语言

完成安装之后，默认语言为英文(en)，如果要使用中文，打开`config/app.php`，将`locale`设置为`zh_CN`即可。

## 自定义语言

如果需要修改`laravel-admin`的语言包，可以用下面的方式解决

复制`vendor/encore/laravel-admin/lang`到项目的`resources/lang/admin`，然后在`app/Admin/bootstrap.php`文件中加入代码：

```php
app('translator')->addNamespace('admin', resource_path('lang/admin'));
```

如果将系统语言locale设置为`zh-CN`，可以将`resources/lang/admin`目录下的`zh_CN`目录重命名为`zh-CN`即可，更新`laravel-admin`的时候，要做相应修改。

## 关于扩展自定义组件

`laravel-admin`默认引用了大量前端资源，如果有网络问题或者有不需要使用的组件，可以参考[form组件管理](/docs/zh/field-management.md)将其移除。

关于富文本编辑器，由于静态资源包文件普遍太大，所以`laravel-admin`默认通过cdn的方式引用`ckeditor`，建议大家根据自己的需求扩展编辑器，自行配置。

## 关于前端资源问题

如果需要使用自己的前端文件，可以在`app/Admin/bootstrap.php`中引入：

```php
Admin::css('path/to/your/css');
Admin::css('path/to/your/js');
```


## 自定义登陆页面和登陆逻辑

在路由文件`app/Admin/routes.php`中，覆盖掉登陆页面和登陆逻辑的路由，即可实现自定义的功能

```php
Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('auth/login', 'AuthController@getLogin');
    $router->post('auth/login', 'AuthController@postLogin');
    
});

```

在自定义的路由器AuthController中的`getLogin`、`postLogin`方法里分别实现自己的登陆页面和登陆逻辑。

参考控制器文件[AuthController.php](https://github.com/z-song/laravel-admin/blob/master/src/Controllers/AuthController.php)，视图文件[login.blade.php](https://github.com/z-song/laravel-admin/blob/master/views/login.blade.php)

## 更新静态资源

如果遇到更新之后,部分组件不能正常使用,那有可能是`laravel-admin`自带的静态资源有更新了,所以需要手动去用`vendor/encore/laravel-admin/assets`的静态资源覆盖掉`public/packages`目录下的静态资源文件,覆盖完成之后不要忘记清理浏览器缓存.