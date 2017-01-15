<?php

namespace Encore\Admin\Field\DataField;

use Closure;
use Encore\Admin\Field\DataField;

class Display extends DataField
{
    protected $callback;

    /**
     * @deprecated
     *
     * @param Closure $callback
     */
    public function format(Closure $callback)
    {
        $this->with($callback);
    }

    public function with(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function render()
    {
        if ($this->callback instanceof Closure) {

            $callback = $this->callback->bindTo($this->form->model());

            $this->value = call_user_func($callback, $this->value);
        }

        return parent::render();
    }
}
