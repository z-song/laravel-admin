<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Table;
use Illuminate\Support\Collection;

class ColumnSelector extends AbstractTool
{
    const SELECT_COLUMN_NAME = '_columns_';

    /**
     * @var array
     */
    protected static $ignored = [
        Table\Column::SELECT_COLUMN_NAME,
        Table\Column::ACTION_COLUMN_NAME,
    ];

    /**
     * Create a new Export button instance.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return Collection
     */
    protected function getTableColumns()
    {
        return $this->table->columns()->reject(function ($column) {
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
        if (!$this->table->showColumnSelector()) {
            return '';
        }

        return Admin::view('admin::table.column-selector', [
            'columns'  => $this->getTableColumns(),
            'visible'  => $this->table->visibleColumnNames(),
            'defaults' => $this->table->getDefaultVisibleColumnNames(),
        ]);
    }
}
