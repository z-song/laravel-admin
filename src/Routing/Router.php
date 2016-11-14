<?php

namespace Encore\Admin\Routing;

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router as LaravelRouter;

class Router
{
    /**
     * Laravel Router.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Admin routes group attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * All admin routes.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Create a new admin router instance.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(LaravelRouter $router)
    {
        $this->router = $router;

        $this->prepareAttributes();
        $this->setAdminRoutes();
    }

    /**
     * Prepare admin route group attributes.
     *
     * @return void
     */
    protected function prepareAttributes()
    {
        $this->attributes = [
            'prefix'        => config('admin.prefix'),
            'namespace'     => Admin::controllerNamespace(),
            'middleware'    => ['web', 'admin'],
        ];
    }

    /**
     * Set auth route.
     *
     * @return void
     */
    protected function setAdminRoutes()
    {
        $attributes = $this->attributes;
        $attributes['namespace'] = 'Encore\Admin\Controllers';

        $this->router->group($attributes, function ($router) {

            $attributes = ['middleware' => 'admin.permission:allow,administrator'];

            $router->group($attributes, function ($router) {
                $router->resources([
                    'auth/users'       => 'UserController',
                    'auth/roles'       => 'RoleController',
                    'auth/permissions' => 'PermissionController',
                    'auth/menu'        => 'MenuController',
                    'auth/logs'        => 'LogController',
                ]);
            });

            $router->get('auth/login', 'AuthController@getLogin');
            $router->post('auth/login', 'AuthController@postLogin');
            $router->get('auth/logout', 'AuthController@getLogout');
        });
    }

    /**
     * Register admin routes.
     *
     * @return void
     */
    public function register()
    {
        $this->router->group($this->attributes, function ($router) {
            foreach ($this->routes as $method => $arguments) {
                foreach ($arguments as $argument) {
                    call_user_func_array([$router, $method], $argument);
                }
            }
        });
    }

    /**
     * Dynamically add routes to admin router.
     *
     * @param string $method
     * @param array  $arguments
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->router, $method)) {
            $this->routes[$method][] = $arguments;
        }
    }
}
