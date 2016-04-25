<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class BootstrapSwitch extends Field
{
    protected $values;

    protected $css = [
        'bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'
    ];

    protected $js = [
        'bootstrap-switch/dist/js/bootstrap-switch.min.js'
    ];

    public function render()
    {
        $this->script = "$('.{$this->id}').bootstrapSwitch();";

        return parent::render()->with(['values' => $this->values]);
    }

    public function values($values)
    {
        $this->values = $values;

        return $this;
    }
}