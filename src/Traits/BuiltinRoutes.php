<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Http\Controllers\AuthController;

trait BuiltinRoutes
{
    /**
     * Register the laravel-admin builtin routes.
     *
     * @return void
     *
     * @deprecated Use Admin::routes() instead();
     */
    public function registerAuthRoutes()
    {
        $this->routes();
    }

    /**
     * Register the laravel-admin builtin routes.
     *
     * @return void
     */
    public function routes()
    {
        $attributes = [
            'prefix'     => config('admin.route.prefix'),
            'middleware' => config('admin.route.middleware'),
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('\Encore\Admin\Http\Controllers')->group(function ($router) {

                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'UserController')->names('admin.auth.users');
                $router->resource('auth/roles', 'RoleController')->names('admin.auth.roles');
                $router->resource('auth/permissions', 'PermissionController')->names('admin.auth.permissions');
                $router->resource('auth/menu', 'MenuController', ['except' => ['create']])->names('admin.auth.menu');
                $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']])->names('admin.auth.logs');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('admin.handle-form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('admin.handle-action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('admin.handle-selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('admin.handle-renderable');
            });

            $authController = config('admin.auth.controller', AuthController::class);

            /* @var \Illuminate\Routing\Router $router */
            $router->get('auth/login', $authController.'@getLogin')->name('admin.login');
            $router->post('auth/login', $authController.'@postLogin');
            $router->get('auth/logout', $authController.'@getLogout')->name('admin.logout');
            $router->get('auth/setting', $authController.'@getSetting')->name('admin.setting');
            $router->put('auth/setting', $authController.'@putSetting');
        });
    }
}
