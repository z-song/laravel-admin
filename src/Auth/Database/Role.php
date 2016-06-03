<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('admin.database.roles_table');

        parent::__construct($attributes);
    }

    /**
     * A role belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function administrators()
    {
        $pivotTable = config('admin.database.role_users_table');

        return $this->belongsToMany(Administrator::class, $pivotTable);
    }

    /**
     * A role belongs to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $pivotTable = config('admin.database.role_permissions_table');

        return $this->belongsToMany(Permission::class, $pivotTable);
    }

    /**
     * Check user has permission.
     *
     * @param $permission
     * @return bool
     */
    public function can($permission)
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     * @return bool
     */
    public function cannot($permission)
    {
        return ! $this->can($permission);
    }
}
