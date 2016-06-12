<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('admin.database.permissions_table');

        parent::__construct($attributes);
    }

    /**
     * Permission belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('admin.database.role_permissions_table');

        return $this->belongsToMany(Role::class, $pivotTable, 'permission_id', 'role_id');
    }
}
