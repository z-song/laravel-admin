<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class BootstrapMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        Form::collectFieldAssets();

        Grid::registerColumnDisplayer();

        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            require $bootstrap;
        }

        return $next($request);
    }
}