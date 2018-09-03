<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class Bootstrap
{
    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        Grid::registerColumnDisplayer();

        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            require $bootstrap;
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
