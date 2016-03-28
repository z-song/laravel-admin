<?php

namespace Encore\Admin\Grid;

class Cell
{
    protected $row;

    protected $column;

    protected $value;

    public function __construct($row = 0, $column = 0, $value = '')
    {
        $this->row = $row;

        $this->column = $column;

        $this->value = $value;
    }
}
