# 权限控制

`laravel-admin`已经内置了`RBAC`权限控制模块，展开左侧边栏的`Auth`，下面有用户、权限、角色三项的管理面板，权限控制的使用如下：
```php
use Encore\Admin\Auth\Permission;

class PostController extends Controller
{
    public function create()
    {
        // 检查权限，有create-post权限的角色可以访问
        Permission::check('create-post');
        
        // 'editor', 'developer'两个角色可以访问
        Permission::allow(['editor', 'developer']);
        
        // 'editor', 'developer'两个角色禁止访问
        Permission::deny(['editor', 'developer']);
    }
}
```

#### 其它使用方法

获取当前用户对象
```php
Admin::user();
```

获取当前用户id
```php
Admin::user()->id;
```

获取用户角色
```php
Admin::user()->roles;
```

获取用户角色
```php
Admin::user()->permissions;
```

用户是否某个角色
```php
Admin::user()->isRole('developer');
```

是否有某个权限
```php
Admin::user()->can('create-post');
```

是否没有某个权限
```php
Admin::user()->cannot('delete-post');
```

是否是超级管理员
```php
Admin::user()->isAdministrator();
```

是否是其中的角色
```php
Admin::user()->inRoles(['editor', 'developer']);
```

## 权限中间件

可以在路由配置上结合权限中间件来控制路由的权限

```php

// 允许administrator、editor两个角色访问group里面的路由
Route::group([
    'middleware' => 'admin.permission:allow,administrator,editor',
], function ($router) {

    $router->resource('users', UserController::class);
    ...
    
});

// 禁止developer、operator两个角色访问group里面的路由
Route::group([
    'middleware' => 'admin.permission:deny,developer,operator',
], function ($router) {

    $router->resource('users', UserController::class);
    ...
    
});

// 有edit-post、create-post、delete-post三个权限的用户可以访问group里面的路由
Route::group([
    'middleware' => 'admin.permission:check,edit-post,create-post,delete-post',
], function ($router) {

    $router->resource('posts', PostController::class);
    ...
    
});
```

权限中间件和其它中间件使用方法一致。
