<?php

namespace Encore\Admin\Table\Concerns;

use Encore\Admin\Table\Column;
use Encore\Admin\Table\ColumnGroup;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait CanCombineColumn
{
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var ColumnGroup
     */
    protected $currentGroup;

    /**
     * @param string $title
     * @param \Closure $callback
     *
     * @return ColumnGroup
     */
    public function colgroup(string $title, \Closure $callback)
    {
        $group = new ColumnGroup($title);

        $this->groups[] = $this->currentGroup = $group;

        call_user_func($callback, $this);

        $this->currentGroup = null;

        return $group;
    }

    /**
     * @param Column $column
     */
    public function addGroupColumn(Column $column)
    {
        if ($this->currentGroup) {
            $this->currentGroup->add($column);
        }
    }

    /**
     * @return bool
     */
    public function hasColumnGroup()
    {
        return !empty($this->groups);
    }

    /**
     * @return Collection
     */
    public function getGroupColumns()
    {
        return collect($this->groups)->map->getColumns()->flatten();
    }
}
