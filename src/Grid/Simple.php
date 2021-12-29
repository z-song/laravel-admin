<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;
use Illuminate\Contracts\Support\Renderable;

/**
 * @mixin Grid
 */
class Simple implements Renderable
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var string
     */
    protected $model;

    /**
     * @param null $key
     *
     * @return string
     */
    public function render($key = null)
    {
        $this->grid = new Grid(new $this->model());

        $this->make($key);

        return $this->grid
            ->disableActions()
            ->disableBatchActions()
            ->disableExport()
            ->disableColumnSelector()
            ->disableCreateButton()
            ->disablePerPageSelector()
            ->paginate(10)
            ->expandFilter()
            ->render();
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->grid, $name], $arguments);
    }
}
