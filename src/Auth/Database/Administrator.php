<?php

namespace Encore\Admin\Auth\Database;

use App\Models\Traits\TAdmin;
use App\User\Controllers\Auth\Traits\CanResetPassword;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as _AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class Administrator.
 * @mixin \Eloquent
 * @property Role[] $roles
 */
class Administrator extends Model implements _AuthenticatableContract,
//    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, AdminBuilder, HasPermissions,TAdmin, CanResetPassword;

    protected $fillable = [
        'email',
        'username',
        'password',
        'name',
        'avatar'
    ];

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
}
