<?php

namespace Encore\Admin;

use Closure;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Admin
{
    public static $css = [];

    public static $js = [];

    public static $script = [];

    /**
     * @param $model
     * @param callable $callable
     * @return Grid
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param callable $callable
     * @return Form
     */
    public function form($model, Closure $callable)
    {
        return new Form($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @return mixed
     */
    public function getModel($model)
    {
        if($model instanceof EloquentModel) {
            return $model;
        }

        if(is_string($model) && class_exists($model)) {
            return $this->getModel(new $model);
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * @param string $css
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function css($css = '')
    {
        if( ! empty($css)) {
            if(is_string($css)) self::$css[] = $css;
            if(is_array($css)) {
                self::$css = array_merge(self::$css, $css);
            }

            return ;
        }

        return view('admin::partials.css', ['css' => array_unique(self::$css)]);
    }

    /**
     * @param string $js
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function js($js = '')
    {
        if( ! empty($js)) {
            if(is_string($js)) self::$js[] = $js;
            if(is_array($js)) {
                self::$js = array_merge(self::$js, $js);
            }

            return;
        }

        return view('admin::partials.js', ['js' => array_unique(self::$js)]);
    }

    /**
     * @param string $script
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function script($script = '')
    {
        if( ! empty($script)) {
            if(is_string($script)) self::$script[] = $script;
            if(is_array($script)) self::$script = $script;

            return;
        }

        return view('admin::partials.script', ['script' => array_unique(self::$script)]);
    }

    public static function url($url)
    {
        $prefix = app('router')->current()->getPrefix();

        return "/$prefix/" . trim($url, '/');
    }

    public function menu()
    {
        if(Config::has('admin.menu')) {
            return Config::get('admin.menu');
        }
    }

    public function user()
    {
        return auth()->user();
    }
}