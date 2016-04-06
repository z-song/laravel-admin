<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;

class Exporter
{
    protected $grid;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }
}
