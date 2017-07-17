<?php

namespace Encore\Admin\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Encore\Admin\Commands\MakeCommand',
        'Encore\Admin\Commands\MenuCommand',
        'Encore\Admin\Commands\InstallCommand',
        'Encore\Admin\Commands\UninstallCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth'        => \Encore\Admin\Middleware\Authenticate::class,
        'admin.pjax'        => \Encore\Admin\Middleware\PjaxMiddleware::class,
        'admin.log'         => \Encore\Admin\Middleware\OperationLog::class,
        'admin.permission'  => \Encore\Admin\Middleware\PermissionMiddleware::class,
        'admin.bootstrap'   => \Encore\Admin\Middleware\BootstrapMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
            'admin.permission',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../views', 'admin');
        $this->loadRoutesFrom(admin_path('routes.php'));

        $this->publishes([__DIR__.'/../../config' => config_path()],                        'laravel-admin-config');
        $this->publishes([__DIR__.'/../../lang' => resource_path('lang')],                  'laravel-admin-lang');
//        $this->publishes([__DIR__.'/../../views' => resource_path('views/admin')],          'laravel-admin-views');
        $this->publishes([__DIR__.'/../../migrations' => database_path('migrations')],      'laravel-admin-migrations');
        $this->publishes([__DIR__.'/../../assets' => public_path('vendor/laravel-admin')],  'laravel-admin-assets');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('admin.auth'), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
