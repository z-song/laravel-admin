<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form\Actions\_List;
use Encore\Admin\Form\Actions\Action;
use Encore\Admin\Form\Actions\Delete;
use Encore\Admin\Form\Actions\View;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * @var Builder
     */
    protected $form;

    /**
     * @var Collection
     */
    protected $default;

    /**
     * @var array
     */
    protected $defaultTools = ['view', 'delete', 'list'];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Tools should be prepends to default tools.
     *
     * @var Collection
     */
    protected $prepends;

    /**
     * @var bool
     */
    protected $disable = false;

    /**
     * Create a new Tools instance.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->form = $builder;
        $this->default = new Collection();
        $this->appends = new Collection();
        $this->prepends = new Collection();
    }

    /**
     * Append a tools.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        if ($tool instanceof Action) {
            $tool->setModel($this->form->getModel());
        }

        $this->appends->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        if ($tool instanceof Action) {
            $tool->setModel($this->form->getModel());
        }

        $this->prepends->push($tool);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addView()
    {
        if (in_array('view', $this->defaultTools)) {
            $this->default->put('view', new View($this->getViewPath()));
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addList()
    {
        if (in_array('list', $this->defaultTools)) {
            $this->default->put('list', new _List($this->getListPath()));
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDelete()
    {
        if (in_array('delete', $this->defaultTools)) {
            $action = new Delete($this->getListPath());

            $this->default->put('delete', $action->setModel($this->form->getModel()));
        }

        return $this;
    }

    /**
     * Disable all tools.
     *
     * @return $this
     */
    public function disable()
    {
        $this->disable = true;

        return $this;
    }

    /**
     * Disable `list` tool.
     *
     * @return $this
     */
    public function disableList(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultTools, 'list');
        } elseif (!$this->default->has('list')) {
            $this->addList();
        }

        return $this;
    }

    /**
     * Disable `delete` tool.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultTools, 'delete');
        } elseif (!$this->default->has('delete')) {
            $this->addDelete();
        }

        return $this;
    }

    /**
     * Disable `edit` tool.
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultTools, 'view');
        } elseif (!$this->default->has('view')) {
            $this->addView();
        }

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return $this->form->getResource();
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        return $this->getViewPath();
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getViewPath()
    {
        $key = $this->form->getResourceId();

        if ($key) {
            return $this->getListPath().'/'.$key;
        } else {
            return $this->getListPath();
        }
    }

    /**
     * Get parent form of tool.
     *
     * @return Builder
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     *
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
        if ($this->form->isCreating()) {
            $this->disableView();
            $this->disableDelete();
        }

        if (empty($tools)) {
            return '';
        }

        return $tools->map(function ($tool) {
            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string) $tool;
        })->implode(' ');
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        if ($this->disable) {
            return '';
        }

        $this->addView()->addDelete()->addList();

        $output = $this->renderCustomTools($this->prepends);

        foreach ($this->default as $tool) {
            $output .= $tool->render();
        }

        return $output.$this->renderCustomTools($this->appends);
    }
}
