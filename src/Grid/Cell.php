<?php

namespace Encore\Admin\Grid;

class Cell
{
    /**
     * @var int
     */
    protected $row;

    /**
     * @var int
     */
    protected $column;

    /**
     * @var string
     */
    protected $value;

    /**
     * Cell constructor.
     * @param int $row
     * @param int $column
     * @param string $value
     */
    public function __construct($row = 0, $column = 0, $value = '')
    {
        $this->row = $row;

        $this->column = $column;

        $this->value = $value;
    }
}
