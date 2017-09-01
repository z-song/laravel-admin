<?php

namespace Encore\Admin;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Illuminate\Support\Facades\Route;

abstract class Extension
{
    public static function config($key, $default = null)
    {
        $name = array_search(get_called_class(), Admin::$extensions);

        $key = sprintf('admin.extensions.%s.%s', strtolower($name), $key);

        return config($key, $default);
    }

    public static function import()
    {
    }

    protected static function routes($callback)
    {
        /* @var \Illuminate\Routing\Router $router */
        Route::group(['prefix' => config('admin.route.prefix')], function ($router) use ($callback) {
            $attributes = array_merge([
                'middleware' => config('admin.route.middleware'),
            ], static::config('route', []));

            $router->group($attributes, $callback);
        });
    }

    protected static function createMenu($title, $uri, $icon = 'fa-bars', $parentId = 0)
    {
        $lastOrder = Menu::max('order');

        Menu::create([
            'parent_id' => $parentId,
            'order'     => $lastOrder + 1,
            'title'     => $title,
            'icon'      => $icon,
            'uri'       => $uri,
        ]);
    }

    protected static function createPermission($name, $slug, $path)
    {
        Permission::create([
            'name'          => $name,
            'slug'          => $slug,
            'http_path'     => '/'.trim($path, '/'),
        ]);
    }
}
