# Access Control


`laravel-admin` has built-in` RBAC` permissions control module, expand the left sidebar `Auth`, you can see user, permissions and roles management panel, the use of permissions control as follows:

```php
use Encore\Admin\Auth\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        // Check permissions, the role of the user permissions can be accessed
        Permission::check('user');
        
        // 'editor' and 'developer' role can be accessed
        Permission::allow(['editor', 'developer']);
        
        // 'editor' and 'developer' role are denied
        Permission::deny(['editor', 'developer']);
    }
}
```

#### Other usage

Get current user object.
```php
Admin::user();
```

Get current user id.
```php
Admin::user()->id;
```

Get user's roles.
```php
Admin::user()->roles;
```

Get user's permissions.
```php
Admin::user()->permissions;
```

User is role.
```php
Admin::user()->isRole('developer');
```

User has permission.
```php
Admin::user()->can('create-post');
```

User don't has permission.
```php
Admin::user()->cannot('delete-post');
```

Is user super administrator.
```php
Admin::user()->isAdministrator();
```

Is user in one of roles.
```php
Admin::user()->inRoles(['editor', 'developer']);
```

## Permission middleware

You can use permission middleware in the routes to control the routing permission

```php

// Allow roles `administrator` and `editor` access the routes under group.
Route::group([
    'middleware' => 'admin.permission:allow,administrator,editor',
], function ($router) {

    $router->resource('users', UserController::class);
    ...
    
});

// Deny roles `developer` and `operator` access the routes under group.
Route::group([
    'middleware' => 'admin.permission:deny,developer,operator',
], function ($router) {

    $router->resource('users', UserController::class);
    ...
    
});

// User has permission `edit-post`、`create-post` and `delete-post` can access routes under group.
Route::group([
    'middleware' => 'admin.permission:check,edit-post,create-post,delete-post',
], function ($router) {

    $router->resource('posts', PostController::class);
    ...
    
});
```

The usage of permission middleware is just as same as other middleware.
