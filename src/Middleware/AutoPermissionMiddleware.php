<?php
/**
 * Created by PhpStorm.
 * User: never615
 * Date: 10/03/2017
 * Time: 8:36 PM
 *
 * You need set permission's slug by routeName or url( auth/roles of https://xxx.com/admin/auth/roles )
 */
namespace Encore\Admin\Middleware;

use Closure;
use Encore\Admin\Auth\Database\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AutoPermissionMiddleware
{
    protected $except = [
    ];

    /**
     * Handle an incoming request.
     *
     * @param         $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $currentUrl = $request->path();
        $currentUrl = substr($currentUrl, 6);  //admin
        $currentRouteName = Route::currentRouteName();

        if (Auth::guard("admin")->user()->isAdministrator()) {
            //pass
            return $next($request);
        } else {
            $permission = Permission::where('slug', $currentUrl)->orWhere('slug', $currentRouteName)->first();
            if ($permission) {
                if (Auth::guard("admin")->user()->can($permission->slug)) {
                    //pass
                    return $next($request);
                } else {
                    //denied
                    throw new AccessDeniedHttpException(trans("errors.deny"));
                }
            } else {
                //Does not have to create this permission.
                return $next($request);
            }
        }
    }
}
