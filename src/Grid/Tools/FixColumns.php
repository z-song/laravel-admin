<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Illuminate\Support\Collection;

class FixColumns
{
    /**
     * @var Grid
     */
    protected $grid;

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
    protected $view = 'admin::grid.fixed-table';

    /**
     * FixColumns constructor.
     *
     * @param Grid $grid
     * @param int  $head
     * @param int  $tail
     */
    public function __construct(Grid $grid, $head, $tail = -1)
    {
        $this->grid = $grid;
        $this->head = $head;
        $this->tail = $tail;

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
        $this->grid->setView($this->view, [
            'allName' => $this->grid->getSelectAllName(),
            'rowName' => $this->grid->getGridRowName(),
        ]);

        return function (Grid $grid) {
            if ($this->head > 0) {
                $this->left = $grid->visibleColumns()->slice(0, $this->head);
            }

            if ($this->tail < 0) {
                $this->right = $grid->visibleColumns()->slice($this->tail);
            }
        };
    }
}
