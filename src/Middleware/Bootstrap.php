<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        Grid::registerColumnDisplayer();

        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            require $bootstrap;
        }

        if (Admin::$booted) {
            call_user_func(Admin::$booted);
        }

        $this->injectFormAssets();

        return $next($request);
    }

    /**
     * Inject assets of all form fields.
     */
    protected function injectFormAssets()
    {
        $assets = Form::collectFieldAssets();

        Admin::css($assets['css']);
        Admin::js($assets['js']);
    }
}
