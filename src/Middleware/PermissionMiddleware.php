<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Auth\Permission;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $args
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$args)
    {
        if (count($args) > 1) {
            $type = array_shift($args);

            if (!method_exists(Permission::class, $type)) {
                throw new \InvalidArgumentException("Invaild permission method [$type].");
            }

            call_user_func_array([Permission::class, $type], [$args]);
        }

        return $next($request);
    }
}
