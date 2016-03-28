<?php

namespace Encore\Admin;

use Encore\Admin\Chart\Bar;
use Encore\Admin\Chart\Line;

class Chart
{
    protected $chart;

    public function __construct(\Closure $callback)
    {
        $callback($this);
    }

    public function line($data, $options = [])
    {
        $this->chart = new Line($data, $options);
    }

    public function bar($data, $options = [])
    {
        $this->chart = new Bar($data, $options);
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return $this->chart->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
