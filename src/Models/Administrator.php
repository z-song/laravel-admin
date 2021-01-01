<?php

namespace Encore\Admin\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Class Administrator.
 */
class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable;
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

    /**
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        if (url()->isValidUrl($avatar)) {
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
     * If User can see menu item.
     *
     * @param Menu $menu
     *
     * @return bool
     */
    public function canSeeMenu($menu)
    {
        return true;
    }

    /**
     * If user can access route.
     *
     * @param Route $route
     *
     * @return bool
     */
    public function canAccessRoute(Route $route)
    {
        return true;
    }
}
