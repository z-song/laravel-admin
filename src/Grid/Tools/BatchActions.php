<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Actions\BatchAction;
use Encore\Admin\Grid\Actions\BatchDelete;
use Illuminate\Support\Collection;

class BatchActions extends AbstractTool
{
    /**
     * @var Collection
     */
    protected $actions;

    /**
     * @var bool
     */
    protected $enableDelete = true;

    /**
     * @var bool
     */
    private $holdAll = false;

    /**
     * BatchActions constructor.
     */
    public function __construct()
    {
        $this->actions = new Collection();

        $this->add(new BatchDelete());
    }

    /**
     * Disable delete.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        $this->enableDelete = !$disable;

        return $this;
    }

    /**
     * Disable delete And Hode SelectAll Checkbox.
     *
     * @return $this
     */
    public function disableDeleteAndHodeSelectAll()
    {
        $this->enableDelete = false;

        $this->holdAll = true;

        return $this;
    }

    /**
     * Add a batch action.
     *
     * @param $title
     * @param BatchAction|null $action
     *
     * @return $this
     */
    public function add(BatchAction $action)
    {
        $this->actions->push($action);

        return $this;
    }

    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->enableDelete) {
            $this->actions->shift();
        }

        $this->actions->each(function ($action) {
            $action->setGrid($this->grid);
        });

        return Admin::view('admin::grid.batch-actions', [
            'all'     => $this->grid->getSelectAllName(),
            'row'     => $this->grid->getGridRowName(),
            'actions' => $this->actions,
            'holdAll' => $this->holdAll,
        ]);
    }
}
