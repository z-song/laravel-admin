<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

/**
 * Class Admin
 * @package Encore\Admin
 */
class Admin
{
    /**
     * @var array
     */
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

    /**
     * @return mixed
     */
    public function user()
    {
        return Auth::guard('admin')->user();
    }
}
