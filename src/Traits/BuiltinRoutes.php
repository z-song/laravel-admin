<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Http\Controllers\AuthController;

trait BuiltinRoutes
{
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
            'as'         => config('admin.route.as'),
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('\Encore\Admin\Http\Controllers')->group(function ($router) {

                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'UserController')->names('auth_users');
                $router->resource('auth/menu', 'MenuController', ['except' => ['create']])->names('auth_menus');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');

                // requirejs配置
                $router->get('_require_config', 'PagesController@requireConfig')->name('require-config');

                $router->fallback('PagesController@error404')->name('error404');
            });

            $authController = config('admin.auth.controller', AuthController::class);

            /* @var \Illuminate\Routing\Router $router */
            $router->get('auth/login', $authController.'@getLogin')->name('login');
            $router->post('auth/login', $authController.'@postLogin')->name('login_post');
            $router->get('auth/logout', $authController.'@getLogout')->name('logout');
            $router->get('auth/setting', $authController.'@getSetting')->name('setting');
            $router->put('auth/setting', $authController.'@putSetting')->name('setting_put');
        });
    }
}
