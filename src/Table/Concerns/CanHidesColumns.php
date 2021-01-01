<?php

namespace Encore\Admin\Table\Concerns;

use Encore\Admin\Table;
use Encore\Admin\Table\Tools\ColumnSelector;
use Illuminate\Support\Collection;

trait CanHidesColumns
{
    /**
     * Default columns be hidden.
     *
     * @var array
     */
    public $hiddenColumns = [];

    /**
     * Remove column selector on table.
     *
     * @param bool $disable
     *
     * @return Table|mixed
     */
    public function disableColumnSelector(bool $disable = true)
    {
        return $this->option('show_column_selector', !$disable);
    }

    /**
     * @return bool
     */
    public function showColumnSelector()
    {
        return $this->option('show_column_selector');
    }

    /**
     * @return string
     */
    public function renderColumnSelector()
    {
        return (new ColumnSelector($this))->render();
    }

    /**
     * Setting default shown columns on table.
     *
     * @param array|string $columns
     *
     * @return $this
     */
    public function hideColumns($columns)
    {
        if (func_num_args()) {
            $columns = (array) $columns;
        } else {
            $columns = func_get_args();
        }

        $this->hiddenColumns = array_merge($this->hiddenColumns, $columns);

        return $this;
    }

    /**
     * Get visible columns from request query.
     *
     * @return array
     */
    protected function getVisibleColumnsFromQuery()
    {
        $columns = explode(',', request(ColumnSelector::SELECT_COLUMN_NAME));

        return array_filter($columns) ?:
            array_values(array_diff($this->columnNames, $this->hiddenColumns));
    }

    /**
     * Get all visible column instances.
     *
     * @return Collection|static
     */
    public function visibleColumns()
    {
        $visible = $this->getVisibleColumnsFromQuery();

        if (empty($visible)) {
            return $this->columns;
        }

        array_push($visible, Table\Column::SELECT_COLUMN_NAME, Table\Column::ACTION_COLUMN_NAME);

        return $this->columns->filter(function (Table\Column $column) use ($visible) {
            return in_array($column->getName(), $visible);
        });
    }

    /**
     * Get all visible column names.
     *
     * @return array
     */
    public function visibleColumnNames()
    {
        $visible = $this->getVisibleColumnsFromQuery();

        if (empty($visible)) {
            return $this->columnNames;
        }

        array_push($visible, Table\Column::SELECT_COLUMN_NAME, Table\Column::ACTION_COLUMN_NAME);

        return collect($this->columnNames)->filter(function ($column) use ($visible) {
            return in_array($column, $visible);
        })->toArray();
    }

    /**
     * Get default visible column names.
     *
     * @return array
     */
    public function getDefaultVisibleColumnNames()
    {
        return array_values(
            array_diff(
                $this->columnNames,
                $this->hiddenColumns,
                [Table\Column::SELECT_COLUMN_NAME, Table\Column::ACTION_COLUMN_NAME]
            )
        );
    }
}
