<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Actions\Delete;
use Encore\Admin\Grid\Actions\Edit;
use Encore\Admin\Grid\Actions\Show;

class DropdownActions extends Actions
{
    /**
     * @var array
     */
    protected $custom = [];

    /**
     * @var array
     */
    protected $default = [];

    /**
     * @var array
     */
    protected $defaultClass = [Edit::class, Show::class, Delete::class];

    protected function script()
    {
        $script = <<<'SCRIPT'
(function ($) {
    $('.table-responsive').on('show.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "inherit" );
    });
    
    $('.table-responsive').on('hide.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "auto" );
    })
})(jQuery);
SCRIPT;

        Admin::script($script);
    }

    /**
     * @param RowAction $action
     *
     * @return $this
     */
    public function add(RowAction $action)
    {
        $this->prepareAction($action);

        array_push($this->custom, $action);

        return $this;
    }

    protected function prependDefaultActions()
    {
        foreach ($this->defaultClass as $class) {
            /** @var RowAction $action */
            $action = new $class();

            $this->prepareAction($action);

            array_push($this->default, $action);
        }
    }

    /**
     * @param RowAction $action
     */
    protected function prepareAction(RowAction $action)
    {
        $action->setGrid($this->grid)
            ->setColumn($this->column)
            ->setRow($this->row);
    }

    /**
     * Disable view action.
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->default, Show::class);
        } elseif (!in_array(Show::class, $this->default)) {
            array_push($this->default, Show::class);
        }

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return $this.
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->default, Delete::class);
        } elseif (!in_array(Delete::class, $this->default)) {
            array_push($this->default, Delete::class);
        }

        return $this;
    }

    /**
     * Disable edit.
     *
     * @return $this.
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->default, Edit::class);
        } elseif (!in_array(Edit::class, $this->default)) {
            array_push($this->default, Edit::class);
        }

        return $this;
    }

    /**
     * @param null|\Closure $callback
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display($callback = null)
    {
        $this->script();

        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        $this->prependDefaultActions();

        $actions = [
            'default' => $this->default,
            'custom'  => $this->custom,
        ];

        return view('admin::grid.dropdown-actions', $actions);
    }
}
