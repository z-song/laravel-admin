<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Illuminate\Support\Collection;

class ColumnSelector extends AbstractTool
{
    const SELECT_COLUMN_NAME = '_columns_';

    /**
     * @var array
     */
    protected static $ignored = [
        Grid\Column::SELECT_COLUMN_NAME,
        Grid\Column::ACTION_COLUMN_NAME,
    ];

    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Collection
     */
    protected function getGridColumns()
    {
        return $this->grid->columns()->reject(function ($column) {
            return in_array($column->getName(), static::$ignored);
        })->map(function ($column) {
            return [$column->getName() => $column->getLabel()];
        })->collapse();
    }

    /**
     * Ignore a column to display in column selector.
     *
     * @param string|array $name
     */
    public static function ignore($name)
    {
        static::$ignored = array_merge(static::$ignored, (array) $name);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showColumnSelector()) {
            return '';
        }

        return Admin::component('admin::components.grid-column-selector', [
            'columns'  => $this->getGridColumns(),
            'visible'  => $this->grid->visibleColumnNames(),
            'defaults' => $this->grid->getDefaultVisibleColumnNames(),
        ]);
    }
}
