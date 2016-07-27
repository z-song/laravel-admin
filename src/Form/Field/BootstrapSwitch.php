<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class BootstrapSwitch extends Field
{
    protected $values;

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
