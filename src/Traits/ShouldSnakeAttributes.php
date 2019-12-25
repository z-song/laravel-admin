<?php

namespace Encore\Admin\Traits;

trait ShouldSnakeAttributes
{
    /**
     * Indicates if model should snake attribute name.
     *
     * @return bool
     */
    public function shouldSnakeAttributes()
    {
        $class = get_class($this->model);

        return $class::$snakeAttributes;
    }
}
