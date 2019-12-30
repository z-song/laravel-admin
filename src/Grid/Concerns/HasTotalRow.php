<?php

namespace Encore\Admin\Grid\Concerns;

use Closure;
use Encore\Admin\Grid\Tools\TotalRow;

trait HasTotalRow
{
    /**
     * @var array
     */
    protected $totalRowColumns = [];

    /**
     * @var array
     */
    protected $totalRowOperations = [];

    /**
     * @param string  $column
     * @param Closure $callback
     *
     * @return $this
     */
    public function addTotalRow($column, $callback)
    {
        $this->totalRowColumns[$column] = $callback;

        if (! isset($this->totalRowOperations[$column])) {
            $this->addTotalOperation($column, 'sum');
        }

        return $this;
    }

    /**
     * @param string $column
     * @param string $operation
     *
     * @return $this
     */
    public function addTotalOperation($column, $operation, $callback = null)
    {
        if (! is_null($callback)) {
            $this->totalRowColumns[$column] = $callback;
        }

        $this->totalRowOperations[$column] = $operation;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function renderTotalRow($columns = null)
    {
        if (empty($this->totalRowColumns)) {
            return '';
        }

        $query = $this->model()->getQueryBuilder();

        $totalRow = new TotalRow($query, $this->totalRowColumns, $this->totalRowOperations);

        $totalRow->setGrid($this);

        if ($columns) {
            $totalRow->setVisibleColumns($columns);
        }

        return $totalRow->render();
    }
}
