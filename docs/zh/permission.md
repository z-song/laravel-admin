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
