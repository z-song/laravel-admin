<?php

namespace Encore\Admin\Form\Field;

use Closure;
use Encore\Admin\Form\Field;

class Display extends Field
{
    protected $callback;

    public function with(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function render()
    {
        if (is_null($this->value) AND is_null($this->default)){
            return '';
        }
        
        if ($this->callback instanceof Closure) {
            $this->value = $this->callback->call($this->form->model(), $this->value);
        }

        return parent::render();
    }
}
