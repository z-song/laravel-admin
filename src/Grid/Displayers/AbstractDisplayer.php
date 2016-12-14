<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Grid;
use Illuminate\Contracts\Support\Renderable;

abstract class AbstractDisplayer
{
    protected $value;

    protected $row;

    protected $grid;

    public function accept($value)
    {
        $this->value = $value;
    }

    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function setRow($row)
    {
        $this->row = $row;
    }

    abstract public function display();
}