<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Tree;

trait AdminBuilder
{
    public static function grid($callback)
    {
        return new Grid(new static, $callback);
    }

    public static function form($callback)
    {
        Form::registerBuiltinFields();

        return new Form(new static, $callback);
    }

    public static function tree($callback = null)
    {
        return new Tree(new static, $callback);
    }
}
