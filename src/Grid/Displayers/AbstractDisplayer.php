<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;

abstract class AbstractDisplayer
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Column
     */
    protected $column;

    /**
     * @var \stdClass
     */
    protected $row;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create a new displayer instance.
     *
     * @param mixed     $value
     * @param Grid      grid
     * @param Column    $column
     * @param \stdClass $row
     */
    public function __construct($value, Grid $grid, Column $column, \stdClass $row)
    {
        $this->value    = $value;
        $this->grid     = $grid;
        $this->column   = $column;
        $this->row      = $row;
    }

    protected function getKey()
    {
        return $this->row->{$this->grid->getKeyName()};
    }

    /**
     * Display method.
     *
     * @return mixed
     */
    abstract public function display();
}