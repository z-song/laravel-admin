<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Http\Controllers\Auth\AuthController;
use Encore\Admin\Http\Controllers\Auth\MenuController;
use Encore\Admin\Http\Controllers\Auth\UserController;

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
            'as'         => config('admin.route.as').'.',
        ];

        app('router')->group($attributes, function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            // Auth
            $authController = config('admin.auth.controller', AuthController::class);
            $router->get('login', $authController.'@getLogin')->name('login');
            $router->post('login', $authController.'@postLogin')->name('login_post');
            $router->get('logout', $authController.'@getLogout')->name('logout');
            $router->get('self_setting', $authController.'@getSetting')->name('self_setting');
            $router->put('self_setting', $authController.'@putSetting')->name('self_setting_put');
            // User
            $userController = config('admin.database.users_controller', UserController::class);
            $router->resource('auth_users', $userController)->names('auth_users');
            // Menu
            $menuController = config('admin.database.menus_controller', MenuController::class);
            $router->resource('auth_menus', $menuController)->except(['create', 'show'])->names('auth_menus');

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('Encore\\Admin\\Http\\Controllers')->group(function ($router) {
                /* @var \Illuminate\Routing\Router $router */
                $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');
                // requirejs配置
                $router->get('_require_config', 'PagesController@requireConfig')->name('require_config');

                $router->fallback('PagesController@error404')->name('error404');
            });
        });
    }
}
