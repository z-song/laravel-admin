<?php

namespace Encore\Admin\Table;

use Encore\Admin\Widgets\Tooltip;
use Illuminate\Support\Arr;

class ColumnGroup
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var string
     */
    protected $tip = '';

    /**
     * ColumnGroup constructor.
     *
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param Column $column
     */
    public function add(Column $column)
    {
        $column->setGroup($this);

        $this->columns[] = $column;
    }

    /**
     * @param string|null $message
     *
     * @return string
     */
    public function help(string $message = null)
    {
        if (func_num_args() == 0) {
            return $this->tip;
        }

        $this->tip = new Tooltip($message);
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->columns);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
