<?php

namespace Encore\Admin\Widgets;

abstract class Widget
{
    abstract public function render();

    public function __toString()
    {
        return $this->render();
    }
}