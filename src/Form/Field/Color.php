<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Color extends Field
{
    public function render()
    {
        $this->script = "$('#{$this->id}').colorpicker();";

        return parent::render();
    }
}
