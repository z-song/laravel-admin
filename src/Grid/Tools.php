<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Encore\Admin\Grid\Tools\BatchActions;
use Encore\Admin\Grid\Tools\RefreshButton;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * Parent grid.
     *
     * @var Grid
     */
    protected $grid;

    /**
     * Collection of tools.
     *
     * @var Collection
     */
    protected $tools;

    /**
     * Create a new Tools instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->tools = new Collection();

        $this->appendDefaultTools();
    }

    /**
     * Append default tools.
     */
    protected function appendDefaultTools()
    {
        $this->append(new BatchActions());

        $this->append(new RefreshButton());
    }

    /**
     * Append tools.
     *
     * @param AbstractTool $tool
     *
     * @return $this
     */
    public function append(AbstractTool $tool)
    {
        $this->tools->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param AbstractTool $tool
     *
     * @return $this
     */
    public function prepend(AbstractTool $tool)
    {
        $this->tools->prepend($tool);

        return $this;
    }

    /**
     * Render header tools bar.
     *
     * @return string
     */
    public function render()
    {
        return $this->tools->map(function (AbstractTool $tool) {

            return $tool->setGrid($this->grid)->render();

        })->implode(' ');
    }
}
