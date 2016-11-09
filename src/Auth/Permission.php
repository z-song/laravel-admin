<?php

namespace Encore\Admin\Auth;

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return bool|null
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
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
     * @return bool|null
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (!Auth::guard('admin')->user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return bool|null
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Auth::guard('admin')->user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    protected static function error()
    {
        $content = Admin::content(function ($content) {
            $content->body(view('admin::deny'));
        });

        response($content)->send();
        exit;
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
