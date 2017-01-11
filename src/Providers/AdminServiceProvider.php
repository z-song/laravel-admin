<?php

namespace Encore\Admin\Providers;

use Encore\Admin\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'MakeCommand',
        'MenuCommand',
        'InstallCommand',
        'UninstallCommand',
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
        $this->loadTranslationsFrom(__DIR__.'/../../lang/', 'admin');

        $this->publishes([__DIR__.'/../../config/admin.php' => config_path('admin.php')], 'laravel-admin');
        $this->publishes([__DIR__.'/../../assets' => public_path('packages/admin')], 'laravel-admin');

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;

            $this->app['admin.router']->register();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();

            $loader->alias('Admin', \Encore\Admin\Facades\Admin::class);

            $this->setupAuth();
        });

        $this->setupClassAliases();
        $this->registerRouteMiddleware();
        $this->registerCommands();

        $this->registerRouter();
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function setupAuth()
    {
        config([
            'auth.guards.admin.driver'    => 'session',
            'auth.guards.admin.provider'  => 'admin',
            'auth.providers.admin.driver' => 'eloquent',
            'auth.providers.admin.model'  => 'Encore\Admin\Auth\Database\Administrator',
        ]);
    }

    /**
     * Setup the class aliases.
     *
     * @return void
     */
    protected function setupClassAliases()
    {
        $aliases = [
            'admin.router'  => \Encore\Admin\Routing\Router::class,
        ];

        foreach ($aliases as $key => $alias) {
            $this->app->alias($key, $alias);
        }
    }

    /**
     * Register admin routes.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton('admin.router', function ($app) {
            return new Router($app['router']);
        });
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

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command) {
            $this->commands('Encore\Admin\Commands\\'.$command);
        }
    }
}
