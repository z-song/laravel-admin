<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

trait BuiltinRoutes
{
    /**
     * Register the laravel-admin builtin routes.
     *
     * @return void
     */
    public function routes(): void
    {
        $attributes = [
            'prefix'     => config('admin.route.prefix'),
            'middleware' => config('admin.route.middleware'),
            'as'         => config('admin.route.as'),
        ];

        Route::group($attributes, static function () {

            /* @var \Illuminate\Support\Facades\Route $router */
            Route::namespace('\Encore\Admin\Http\Controllers')->group(static function () {

                /* @var \Illuminate\Routing\Router $router */
                Route::resource('auth/users', 'UserController')->names('auth_users');
                Route::resource('auth/menu', 'MenuController', ['except' => ['create']])->names('auth_menus');

                Route::post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                Route::post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                Route::get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                Route::get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');

                // requirejs
                Route::get('_require_config', 'PagesController@requireConfig')->name('require-config');

                Route::fallback('PagesController@error404')->name('error404');
            });

            $authController = config('admin.auth.controller', AuthController::class);

            /* @var \Illuminate\Routing\Router $router */
            Route::get('auth/login', $authController.'@getLogin')->name('login');
            Route::post('auth/login', $authController.'@postLogin')->name('login_post');
            Route::get('auth/logout', $authController.'@getLogout')->name('logout');
            Route::get('auth/setting', $authController.'@getSetting')->name('setting');
            Route::put('auth/setting', $authController.'@putSetting')->name('setting_put');
        });
    }
}
