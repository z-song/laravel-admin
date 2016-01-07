<?php

namespace Encore\Admin\Providers;

use Encore\Admin\Auth\AuthManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    protected $commands = [
        'InstallCommand',
        'MakeCommand'
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth' => \Encore\Admin\Middleware\Authenticate::class,
    ];

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

        $this->registerRouteMiddleware();

        $this->registerCommands();
    }

    public function boot()
    {
        require __DIR__ .'/../helpers.php';

        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('admin.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../migrations/' => base_path('/database/migrations'),
        ], 'migrations');
        
        if (file_exists($routes = admin_path('routes.php')))
        {
            require $routes;
        }

        if (file_exists($menu = admin_path('menu.php')))
        {
            $menu = require $menu;
            config(['admin.menu' => $menu]);
        }
    }

    protected function registerRouteMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->middleware($key, $middleware);
        }
    }

    protected function registerCommands()
    {
        foreach ($this->commands as $command)
        {
            $this->commands('Encore\Admin\Commands\\' . $command);
        }
    }
}