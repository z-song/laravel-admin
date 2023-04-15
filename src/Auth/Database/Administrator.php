<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasPermissions;
    use DefaultDatetimeFormat;

    protected $fillable = ['username', 'password', 'name', 'avatar'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.users_table'));

        parent::__construct($attributes);
    }

    protected function getGeneralCacheKey(): string
    {
        return sprintf('laravel-admin.admin.%d', $this->id);
    }

    /**
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        if ($avatar && url()->isValidUrl($avatar)) {
            return $avatar;
        }

        $disk = config('admin.upload.disk');

        if ($avatar && array_key_exists($disk, config('filesystems.disks'))) {
            return Storage::disk(config('admin.upload.disk'))->url($avatar);
        }

        $default = config('admin.default_avatar') ?: '/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg';

        return admin_asset($default);
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_users_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        $pivotTable = config('admin.database.user_permissions_table');

        $relatedModel = config('admin.database.permissions_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'permission_id');
    }

    /*
     * Enable caching of roles
     */

    protected function getRolesCacheKey(): string
    {
        return $this->getGeneralCacheKey() . '.roles';
    }

    public function clearRolesCache(): bool
    {
        return Cache::forget($this->getRolesCacheKey());
    }

    public function getRolesAttribute()
    {
        if ($this->relationLoaded('roles')) {
            return $this->getRelationValue('roles');
        }

        $roles = Cache::remember($this->getRolesCacheKey(), now()->addHour(), function () {
            $this->loadMissing('roles.permissions');
            return $this->getRelationValue('roles');
        });

        $this->setRelation('roles', $roles);

        return $roles;
    }

    /*
     * Enable caching of permissions
     */

    protected function getPermissionsCacheKey(): string
    {
        return $this->getGeneralCacheKey() . '.permissions';
    }

    public function clearPermissionsCache(): bool
    {
        return Cache::forget($this->getPermissionsCacheKey());
    }

    public function getPermissionsAttribute()
    {
        if ($this->relationLoaded('permissions')) {
            return $this->getRelationValue('permissions');
        }

        $permissions = Cache::remember($this->getPermissionsCacheKey(), now()->addHour(), function () {
            return $this->getRelationValue('permissions');
        });

        $this->setRelation('permissions', $permissions);

        return $permissions;
    }

    public function clearCaches()
    {
        $this->clearRolesCache();
        $this->clearPermissionsCache();
        $this->clearHasPermissionsCaches();
        Cache::forget($this->getGeneralCacheKey() . '.user');
    }
}
