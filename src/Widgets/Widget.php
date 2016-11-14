<?php

namespace Encore\Admin\Widgets;

abstract class Widget
{
    /**
     * @return mixed
     */
    abstract public function render();

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->render();
    }
}
