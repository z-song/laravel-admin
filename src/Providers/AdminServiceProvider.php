<?php

namespace Encore\Admin\Providers;

use Encore\Admin\Auth\AuthManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ .'/../../views', 'admin');

        $this->app->booting(function () {
            $loader  =  AliasLoader::getInstance();

            $loader->alias('Admin', \Encore\Admin\Facades\Admin::class);
        });

        $this->app->bindShared('admin.auth', function($app)
        {
            return new AuthManager($app);
        });
    }

    public function boot()
    {
        if (file_exists($routes = app_path('/Admin/routes.php')))
        {
            require $routes;
        }

        if (file_exists($menu = app_path('/Admin/menu.php')))
        {
            $menu = require $menu;

            config(['admin.menu' => $menu]);
        }
    }
}