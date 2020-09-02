<?php

namespace Encore\Admin\Show;

use Encore\Admin\Show;
use Encore\Admin\Show\Actions\_List;
use Encore\Admin\Show\Actions\Action;
use Encore\Admin\Show\Actions\Delete;
use Encore\Admin\Show\Actions\Edit;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * The panel that holds this tool.
     *
     * @var Show
     */
    protected $show;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var Collection
     */
    protected $default;

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
     * Tools constructor.
     *
     * @param Show $show
     */
    public function __construct(Show $show)
    {
        $this->show = $show;
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
     * Get resource path.
     *
     * @return string
     */
    public function getResource()
    {
        if (is_null($this->resource)) {
            $this->resource = $this->show->getResourcePath();
        }

        return $this->resource;
    }

    /**
     * @return $this
     */
    protected function addEdit()
    {
        $this->default->put('edit', new Edit($this->getEditPath()));

        return $this;
    }

    /**
     * @return $this
     */
    protected function addList()
    {
        $this->default->put('list', new _List($this->getListPath()));

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDelete()
    {
        $action = new Delete($this->getListPath());

        $this->default->put('delete', $action->setModel($this->show->getModel()));

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
            $this->default->pull('list');
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
            $this->default->pull('delete');
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
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            $this->default->pull('edit');
        } elseif (!$this->default->has('edit')) {
            $this->addEdit();
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
        return ltrim($this->getResource(), '/');
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getEditPath()
    {
        $key = $this->show->getModel()->getKey();

        return $this->getListPath().'/'.$key.'/edit';
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        $key = $this->show->getModel()->getKey();

        return $this->getListPath().'/'.$key;
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
        $this->addEdit()
            ->addDelete()
            ->addList();

        $output = $this->renderCustomTools($this->prepends);

        foreach ($this->default as $tool) {
            $output .= $tool->render();
        }

        return $output.$this->renderCustomTools($this->appends);
    }
}
