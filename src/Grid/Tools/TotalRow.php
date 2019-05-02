<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid\Column;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

class TotalRow extends AbstractTool
{
    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $columns;

    /**
     * TotalRow constructor.
     *
     * @param Builder $query
     * @param array $columns
     */
    public function __construct($query, array $columns)
    {
        $this->query   = $query;

        $this->columns = $columns;
    }

    /**
     * Get total value of current column.
     *
     * @param string $column
     * @param mixed $display
     *
     * @return mixed
     */
    protected function total($column, $display)
    {
        if (!is_callable($display) && !is_null($display)) {
            return $display;
        }

        $sum = $this->query->sum($column);

        if (is_callable($display)) {
            return call_user_func($display, $sum);
        }

        return $sum;
    }

    /**
     * Render total-row.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $columns = $this->getGrid()->columns()->flatMap(function (Column $column) {

            $name = $column->getName();

            $total = ($display = Arr::get($this->columns, $name)) ? $this->total($name, $display) : '';

            return [$name => $total];

        })->toArray();

        return view('admin::grid.total-row', compact('columns'));
    }
}