<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class SwitchField extends Field
{
    const STATE_ON  = 1;
    const STATE_OFF = 0;

    protected $js = [
        'bootstrap-switch/dist/js/bootstrap-switch.min.js'
    ];

    protected $css = [
        'bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'
    ];

    protected $options = [];

    protected $states = [];

    public function __construct($column, $arguments = [])
    {
        $this->initStates();

        parent::__construct($column, $arguments);
    }

    public function initStates()
    {
        $this->states = ['on' => static::STATE_ON, 'off' => static::STATE_OFF];
    }

    public function options($options = [])
    {
        $this->options = array_merge($options);
    }

    public function prepare($value)
    {
        if (isset($this->states[$value])) {
            return $this->options[$value];
        }
    }

    public function render()
    {
        $this->script = "$('#{$this->id}').bootstrapSwitch();";

        return parent::render();
    }
}
