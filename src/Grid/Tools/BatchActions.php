<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
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
     * BatchActions constructor.
     */
    public function __construct()
    {
        $this->actions = new Collection();

        $this->appendDefaultAction();
    }

    /**
     * Append default action(batch delete action).
     *
     * return void
     */
    protected function appendDefaultAction()
    {
        $this->add(trans('admin::lang.delete'), new BatchDelete());
    }

    /**
     * Disable delete.
     *
     * @return $this
     */
    public function disableDelete()
    {
        $this->enableDelete = false;

        return $this;
    }

    /**
     * Add a batch action.
     *
     * @param string      $title
     * @param BatchAction $abstract
     *
     * @return $this
     */
    public function add($title, BatchAction $abstract)
    {
        $id = $this->actions->count();

        $abstract->setId($id);

        $this->actions->push(compact('id', 'title', 'abstract'));

        return $this;
    }

    /**
     * Setup scripts of batch actions.
     *
     * @return void
     */
    protected function setUpScripts()
    {
        Admin::script($this->script());

        foreach ($this->actions as $action) {
            $abstract = $action['abstract'];
            $abstract->setResource($this->grid->resource());

            Admin::script($abstract->script());
        }
    }

    /**
     * Scripts of BatchActions button groups.
     *
     * @return string
     */
    protected function script()
    {
        return <<<'EOT'

$('.grid-select-all').iCheck({checkboxClass:'icheckbox_minimal-blue'});

$('.grid-select-all').on('ifChanged', function(event) {
    if (this.checked) {
        $('.grid-row-checkbox').iCheck('check');
    } else {
        $('.grid-row-checkbox').iCheck('uncheck');
    }
});

var selectedRows = function () {
    var selected = [];
    $('.grid-row-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    return selected;
}

EOT;
    }

    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->allowBatchDeletion() || !$this->enableDelete) {
            $this->actions->shift();
        }

        $this->setUpScripts();

        return view('admin::grid.batch-actions', ['actions' => $this->actions])->render();
    }
}
