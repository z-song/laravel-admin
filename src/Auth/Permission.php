<?php

namespace Encore\Admin\Auth;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Middleware\Pjax;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([Permission::class, 'check'], $permission);
            });

            return;
        }

        if (Auth::guard('admin')->user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (!Auth::guard('admin')->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Don't check permission.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Auth::guard('admin')->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    public static function error()
    {
        $response = response(Admin::content()->withError(trans('admin.deny')));

        Pjax::respond($response);
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return Auth::guard('admin')->user()->isRole('administrator');
    }
}
