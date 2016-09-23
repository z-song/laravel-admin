<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Color extends AbstractField
{
    public function render()
    {
        $this->script = "$('#{$this->id()}').colorpicker();";

        return parent::render();
    }
}
