<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!Admin::user()) {
            return $next($request);
        }

        if (!Admin::user()->allPermissions()->first(function ($permission) use ($request) {
            return $permission->shouldPassThrough($request);
        })) {
            Permission::error();
        };

        return $next($request);
    }
}
