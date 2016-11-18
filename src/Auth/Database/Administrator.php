<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = ['username', 'password', 'name'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('admin.database.users_table');

        parent::__construct($attributes);
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('admin.database.role_users_table');

        return $this->belongsToMany(Role::class, $pivotTable, 'user_id', 'role_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $pivotTable = config('admin.database.user_permissions_table');

        return $this->belongsToMany(Permission::class, $pivotTable, 'user_id', 'permission_id');
    }

    /**
     * Check if user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if (method_exists($this, 'permissions')) {
            if ($this->permissions()->where('slug', $permission)->exists()) {
                return true;
            }
        }

        foreach ($this->roles as $role) {
            if ($role->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot($permission)
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator()
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     * @return mixed
     */
    public function inRoles($roles = [])
    {
        return $this->roles()->whereIn('slug', (array) $roles)->exists();
    }

    /**
     * If visible for roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function visible($roles)
    {
        if (empty($roles)) {
            return true;
        }

        $roles = array_column($roles, 'slug');

        if ($this->inRoles($roles) || $this->isAdministrator()) {
            return true;
        }

        return false;
    }
}
