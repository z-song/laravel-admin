<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Navbar;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;

/**
 * Class Admin.
 */
class Admin
{
    /**
     * @var Navbar
     */
    protected $navbar;

    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $css = [];

    /**
     * @var array
     */
    public static $js = [];

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \Encore\Admin\Grid
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \Encore\Admin\Form
     */
    public function form($model, Closure $callable)
    {
        return new Form($this->getModel($model), $callable);
    }

    /**
     * Build a tree.
     *
     * @param $model
     *
     * @return \Encore\Admin\Tree
     */
    public function tree($model, Closure $callable = null)
    {
        return new Tree($this->getModel($model), $callable);
    }

    /**
     * @param Closure $callable
     *
     * @return \Encore\Admin\Layout\Content
     */
    public function content(Closure $callable = null)
    {
        return new Content($callable);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function getModel($model)
    {
        if ($model instanceof EloquentModel) {
            return $model;
        }

        if (is_string($model) && class_exists($model)) {
            return $this->getModel(new $model());
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * Get namespace of controllers.
     *
     * @return string
     */
    public function controllerNamespace()
    {
        $directory = config('admin.directory');

        return ltrim(implode('\\',
              array_map('ucfirst',
                  explode(DIRECTORY_SEPARATOR, str_replace(app()->basePath(), '', $directory)))), '\\')
              .'\\Controllers';
    }

    /**
     * Add css or get all css.
     *
     * @param null $css
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function css($css = null)
    {
        if (!is_null($css)) {
            self::$css = array_merge(self::$css, (array) $css);

            return;
        }

        $css = array_get(Form::collectFieldAssets(), 'css', []);

        static::$css = array_merge(static::$css, $css);

        return view('admin::partials.css', ['css' => array_unique(static::$css)]);
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function js($js = null)
    {
        if (!is_null($js)) {
            self::$js = array_merge(self::$js, (array) $js);

            return;
        }

        $js = array_get(Form::collectFieldAssets(), 'js', []);

        static::$js = array_merge(static::$js, $js);

        return view('admin::partials.js', ['js' => array_unique(static::$js)]);
    }

    /**
     * @param string $script
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function script($script = '')
    {
        if (!empty($script)) {
            self::$script = array_merge(self::$script, (array) $script);

            return;
        }

        return view('admin::partials.script', ['script' => array_unique(self::$script)]);
    }

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public static function url($url)
    {
        $prefix = (string) config('admin.url_root');

        if (empty($prefix) || $prefix == '/') {
            return '/'.trim($url, '/');
        }

        return "/$prefix/".trim($url, '/');
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function menu()
    {
        return (new Menu())->toTree();
    }

    /**
     * Get admin title.
     *
     * @return Config
     */
    public function title()
    {
        return config('admin.title');
    }

    /**
     * Get current login user.
     *
     * @return mixed
     */
    public function user()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Set navbar.
     *
     * @param Closure $builder
     */
    public function navbar(Closure $builder)
    {
        call_user_func($builder, $this->getNavbar());
    }

    /**
     * Get navbar object.
     *
     * @return \Encore\Admin\Widgets\Navbar
     */
    public function getNavbar()
    {
        if (is_null($this->navbar)) {
            $this->navbar = new Navbar();
        }

        return $this->navbar;
    }

    public function registerAuthRoutes()
    {
        $attributes = [
            'prefix'        => config('admin.url_root'),
            'namespace'     => 'Encore\Admin\Controllers',
            'middleware'    => ['web', 'admin'],
        ];

        Route::group($attributes, function ($router) {
            $attributes = ['middleware' => 'admin.permission:allow,administrator'];

            /* @var \Illuminate\Routing\Router $router */
            $router->group($attributes, function ($router) {
                $router->resource('auth/users', 'UserController');
                $router->resource('auth/roles', 'RoleController');
                $router->resource('auth/permissions', 'PermissionController');
                $router->resource('auth/menu', 'MenuController', ['except' => ['create']]);
                $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']]);
            });

            $router->get('auth/login', 'AuthController@getLogin');
            $router->post('auth/login', 'AuthController@postLogin');
            $router->get('auth/logout', 'AuthController@getLogout');
            $router->get('auth/setting', 'AuthController@getSetting');
            $router->put('auth/setting', 'AuthController@putSetting');
        });
    }

    public function registerHelpersRoutes($attributes = [])
    {
        $attributes = array_merge([
            'prefix'     => trim(config('admin.url_root'), '/').'/helpers',
            'middleware' => ['web', 'admin'],
        ], $attributes);

        Route::group($attributes, function ($router) {

            /* @var \Illuminate\Routing\Router $router */
            $router->get('terminal/database', 'Encore\Admin\Controllers\TerminalController@database');
            $router->post('terminal/database', 'Encore\Admin\Controllers\TerminalController@runDatabase');
            $router->get('terminal/artisan', 'Encore\Admin\Controllers\TerminalController@artisan');
            $router->post('terminal/artisan', 'Encore\Admin\Controllers\TerminalController@runArtisan');
            $router->get('scaffold', 'Encore\Admin\Controllers\ScaffoldController@index');
            $router->post('scaffold', 'Encore\Admin\Controllers\ScaffoldController@store');
        });
    }
}
