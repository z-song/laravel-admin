<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid\Column;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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
     * @var array
     */
    protected $operations;

    /**
     * @var Collection
     */
    protected $visibleColumns;

    /**
     * TotalRow constructor.
     *
     * @param Builder $query
     * @param array   $columns
     * @param array   $operations
     */
    public function __construct($query, array $columns, array $operations)
    {
        $this->query = $query;

        $this->columns = $columns;

        $this->operations = $operations;
    }

    /**
     * Get total value of current column.
     *
     * @param string $column
     * @param mixed  $display
     * @param string $operation
     *
     * @return mixed
     */
    protected function total($column, $display = null, $operation = 'sum')
    {
        if (! is_callable($display) && ! is_null($display)) {
            return $display;
        }

        $sum = $this->query->$operation($operation == 'count' ? '*' : $column);

        if (is_callable($display)) {
            return call_user_func($display, $sum);
        }

        return $sum;
    }

    /**
     * @param Collection $columns
     */
    public function setVisibleColumns($columns)
    {
        $this->visibleColumns = $columns;
    }

    /**
     * @return Collection|static
     */
    public function getVisibleColumns()
    {
        if ($this->visibleColumns) {
            return $this->visibleColumns;
        }

        return $this->getGrid()->visibleColumns();
    }

    /**
     * Render total-row.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $columns = $this->getVisibleColumns()->map(function (Column $column) {
            $name = $column->getName();

            $total = '';

            if (Arr::has($this->columns, $name)) {
                $total = $this->total($name, Arr::get($this->columns, $name), Arr::get($this->operations, $name));
            }

            return [
                'class' => $column->getClassName(),
                'value' => $total,
            ];
        });

        return view('admin::grid.total-row', compact('columns'));
    }
}
