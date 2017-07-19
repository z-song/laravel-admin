<?php

namespace Encore\Admin;

abstract class Extension
{
    protected static $name = '';

    public static function config($key, $default = null)
    {
        $key = sprintf('admin.extensions.%s.%s', static::$name, $key);

        return config($key, $default);
    }

    public function import()
    {
        
    }
}