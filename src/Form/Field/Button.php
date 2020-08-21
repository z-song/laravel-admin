<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Button extends Field
{
    protected $class = '';

    public function info()
    {
        $this->class = 'btn-info';

        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<SCRIPT

        $('{$this->getElementClassSelector()}').on('$event', function() {
            $callback
        });

SCRIPT;
    }

    public function render()
    {
        if (empty($this->class)) {
            $this->class = admin_theme('btn-');
        }

        return parent::render();
    }
}
