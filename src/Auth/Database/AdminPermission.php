<?php

namespace Encore\Admin\Auth\Database;

trait AdminPermission
{
    /**
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        if ($avatar) {
            return rtrim(config('admin.upload.host'), '/').'/'.trim($avatar, '/');
        }

        return asset('/packages/admin/AdminLTE/dist/img/user2-160x160.jpg');
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('admin.database.role_users_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $pivotTable = config('admin.database.user_permissions_table');

        $relatedModel = config('admin.database.permissions_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'permission_id');
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
            if ($this->permissions->keyBy('slug')->has($permission)) {
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
        return $this->roles->keyBy('slug')->has($role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     *
     * @return mixed
     */
    public function inRoles($roles = [])
    {
        $grantedRoles = $this->roles->keyBy('slug');
        foreach ($roles as $role) {
            if ($grantedRoles->has($role)) {
                return true;
            }
        }
        
        return false;
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
