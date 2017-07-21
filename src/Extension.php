<?php

namespace Encore\Admin;

abstract class Extension
{
    public static function config($key, $default = null)
    {
        $class = explode('\\', get_called_class());

        $name = array_pop($class);

        $key = sprintf('admin.extensions.%s.%s', strtolower($name), $key);

        return config($key, $default);
    }

    public static function import()
    {
        
    }
}