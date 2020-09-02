<?php

namespace Encore\Admin\Table;

use Encore\Admin\Table;
use Illuminate\Contracts\Support\Renderable;

/**
 * @mixin Table
 */
class Simple implements Renderable
{
    /**
     * @var Table
     */
    protected $table;

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
        $this->table = new Table(new $this->model());

        $this->make($key);

        return $this->table
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
        return call_user_func_array([$this->table, $name], $arguments);
    }
}
