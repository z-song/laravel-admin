<?php

namespace Encore\Admin\Table\Concerns;

use Closure;
use Encore\Admin\Table;

trait HasActions
{
    /**
     * Callback for table actions.
     *
     * @var []Closure
     */
    protected $actionsCallback = [];

    /**
     * Actions column display class.
     *
     * @var string
     */
    protected $actionsClass;

    /**
     * Set table action callback.
     *
     * @param Closure|string $actions
     *
     * @return $this
     */
    public function actions($actions)
    {
        if ($actions instanceof Closure) {
            $this->actionsCallback[] = $actions;
        }

        return $this;
    }

    /**
     * @param string $action
     */
    public function dblclick($action = 'select')
    {
        if (in_array($action, ['view', 'edit', 'delete', 'select'])) {
            $this->actions(function (Table\Displayers\DropdownActions $actions) use ($action) {
                $actions->dblclick($action);
            });
        }

        return $this;
    }

    /**
     * Get action display class.
     *
     * @return \Illuminate\Config\Repository|mixed|string
     */
    public function getActionClass()
    {
        if ($this->actionsClass) {
            return $this->actionsClass;
        }

        if ($class = config('admin.table_action_class')) {
            return $class;
        }

        return Table\Displayers\DropdownActions::class;
    }

    /**
     * @param string $actionClass
     *
     * @return $this
     */
    public function setActionClass(string $actionClass)
    {
        if (is_subclass_of($actionClass, Table\Displayers\Actions::class) || ($actionClass == Table\Displayers\Actions::class)) {
            $this->actionsClass = $actionClass;
        }

        return $this;
    }

    /**
     * Disable all actions.
     *
     * @return $this
     */
    public function disableActions(bool $disable = true)
    {
        return $this->option('show_actions', !$disable);
    }

    /**
     * Set table batch-action callback.
     *
     * @param Closure $closure
     *
     * @return $this
     */
    public function batchActions(Closure $closure)
    {
        $this->tools(function (Table\Tools $tools) use ($closure) {
            $tools->batch($closure);
        });

        return $this;
    }

    /**
     * @param bool $disable
     *
     * @return Table|mixed
     */
    public function disableBatchActions(bool $disable = true)
    {
        $this->tools->disableBatchActions($disable);

        return $this->option('show_row_selector', !$disable);
    }

    /**
     * Add `actions` column for table.
     *
     * @return void
     */
    protected function appendActionsColumn()
    {
        if (!$this->option('show_actions')) {
            return;
        }

        $this->addColumn(Table\Column::ACTION_COLUMN_NAME, trans('admin.action'))
            ->displayUsing($this->getActionClass(), [$this->actionsCallback]);
    }

    /**
     * @return $this
     */
    public function contextmenu()
    {
        return $this->setActionClass(Table\Displayers\ContextMenuActions::class);
    }
}
