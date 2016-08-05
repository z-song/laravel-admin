<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class Admin
{
    public static $css = [];

    public static $js = [];

    public static $script = [];

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Grid
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Form
     */
    public function form($model, Closure $callable)
    {
        return new Form($this->getModel($model), $callable);
    }

    /**
     * @param Closure $callable
     *
     * @return Content
     */
    public function content(Closure $callable)
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

        return 'App\\'.ucfirst(basename($directory)).'\\Controllers';
    }

    /**
     * @param string $css
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
//    public static function css($css = '')
//    {
//        if (! empty($css)) {
//            self::$css = array_merge(self::$css, (array) $css);
//
//            return ;
//        }
//
//        return view('admin::partials.css', ['css' => array_unique(self::$css)]);
//    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
//    public static function js()
//    {
//        static::$js['map'] = "http://map.qq.com/api/js?v=2.exp";
//
//        if (config('app.locale') == 'zh_CN') {
//            static::$js['map'] = "http://map.qq.com/api/js?v=2.exp";
//        }
//
//        return view('admin::partials.js', ['js' => array_unique(self::$js)]);
//    }

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

    public static function url($url)
    {
        //$prefix = app('router')->current()->getPrefix();

        $prefix = (string) config('admin.prefix');

        return "/$prefix/".trim($url, '/');
    }

    public function menu()
    {
        return config('admin.menu', []);
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

    public function user()
    {
        return Auth::user();
    }
}
