<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

abstract class Actions extends AbstractDisplayer
{
    protected $disableAll = false;

    public abstract function display();
}
