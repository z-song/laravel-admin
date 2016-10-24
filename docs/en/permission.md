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