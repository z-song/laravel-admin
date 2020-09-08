<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Assets;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Http\Controllers\AuthController;
use Illuminate\Support\Arr;

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
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('\Encore\Admin\Http\Controllers')->group(function ($router) {

                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'UserController')->names('admin.auth.users');
                $router->resource('auth/menu', 'MenuController', ['except' => ['create']])->names('admin.auth.menu');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('admin.handle-form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('admin.handle-action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('admin.handle-selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('admin.handle-renderable');

                $router->fallback('PagesController@error404');
            });

            $authController = config('admin.auth.controller', AuthController::class);

            /* @var \Illuminate\Routing\Router $router */
            $router->get('auth/login', $authController.'@getLogin')->name('admin.login');
            $router->post('auth/login', $authController.'@postLogin');
            $router->get('auth/logout', $authController.'@getLogout')->name('admin.logout');
            $router->get('auth/setting', $authController.'@getSetting')->name('admin.setting');
            $router->put('auth/setting', $authController.'@putSetting');

            $router->get('_require_config.js', function () {

                if ($user = Admin::user()) {
                    $user = Arr::only($user->toArray(), ['id', 'username', 'email', 'name', 'avatar']);
                }

                return view('admin::partials.config', [
                    'requirejs' => Assets::config(),
                    'user'      => $user ?: [],
                    'trans'     => [],
                ]);

            })->name('admin-require-config');
        });
    }
}
