<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

abstract class Actions extends AbstractDisplayer
{
    protected $disableAll = false;

    public abstract function display();
}
