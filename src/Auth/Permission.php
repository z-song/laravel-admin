<?php
/**
 * Created by PhpStorm.
 * User: song
 * Email: zousong@yiban.cn
 * Date: 16/5/26
 * Time: 下午4:14
 */

namespace Encore\Admin\Auth;

use Encore\Admin\Exception\Handle;
use Encore\Admin\Facades\Auth;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     */
    public static function check($permission)
    {
        if (Auth::user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     */
    public static function allow($roles)
    {
        if (! Auth::user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     */
    public static function deny($roles)
    {
        if (Auth::user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     *
     * @param \Exception $e
     */
    protected static function error(\Exception $e = null)
    {
        if (is_null($e)) {
            $e = new \Exception('无权访问');
        }

        response(new Handle($e))->send();
    }
}
