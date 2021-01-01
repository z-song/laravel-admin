<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Table;
use Illuminate\Support\Collection;

class FixColumns
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var int
     */
    protected $head;

    /**
     * @var int
     */
    protected $tail;

    /**
     * @var Collection
     */
    protected $left;

    /**
     * @var Collection
     */
    protected $right;

    /**
     * @var string
     */
    protected $view = 'admin::table.fixed-table';

    /**
     * FixColumns constructor.
     *
     * @param Table $table
     * @param int   $head
     * @param int   $tail
     */
    public function __construct(Table $table, $head, $tail = 1)
    {
        $this->table = $table;
        $this->head = $head;
        $this->tail = abs($tail);

        $this->left = Collection::make();
        $this->right = Collection::make();
    }

    /**
     * @return Collection
     */
    public function leftColumns()
    {
        return $this->left;
    }

    /**
     * @return Collection
     */
    public function rightColumns()
    {
        return $this->right;
    }

    /**
     * @return \Closure
     */
    public function apply()
    {
        $this->table->setView($this->view);

        return function (Table $table) {
            if ($this->head > 0) {
                $this->left = $table->visibleColumns()->slice(0, $this->head);
            }

            if ($this->tail > 0) {
                $this->right = $table->visibleColumns()->slice(-$this->tail);
            }
        };
    }
}
