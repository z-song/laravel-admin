<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid;
use Illuminate\Contracts\Support\Renderable;

abstract class AbstractTool implements Renderable
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Set parent grid.
     *
     * @param Grid $grid
     *
     * @return $this
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public abstract function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
