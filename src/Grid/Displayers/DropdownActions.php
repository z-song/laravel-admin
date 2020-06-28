<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Actions\Delete;
use Encore\Admin\Grid\Actions\Edit;
use Encore\Admin\Grid\Actions\Show;

class DropdownActions extends Actions
{
    protected $view = 'admin::grid.actions.dropdown';

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

    /**
     * Prepend default `edit` `view` `delete` actions.
     */
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
     * @param bool $disable
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Show::class);
        } elseif (!in_array(Show::class, $this->defaultClass)) {
            array_push($this->defaultClass, Show::class);
        }

        return $this;
    }

    /**
     * Disable delete.
     *
     * @param bool $disable
     *
     * @return $this.
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Delete::class);
        } elseif (!in_array(Delete::class, $this->defaultClass)) {
            array_push($this->defaultClass, Delete::class);
        }

        return $this;
    }

    /**
     * Disable edit.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Edit::class);
        } elseif (!in_array(Edit::class, $this->defaultClass)) {
            array_push($this->defaultClass, Edit::class);
        }

        return $this;
    }

    /**
     * @param null|\Closure $callback
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        if ($this->disableAll) {
            return '';
        }

        $this->prependDefaultActions();

        $variables = [
            'default' => $this->default,
            'custom'  => $this->custom,
        ];

        if (empty($variables['default']) && empty($variables['custom'])) {
            return;
        }

        return Admin::component($this->view, $variables);
    }
}
