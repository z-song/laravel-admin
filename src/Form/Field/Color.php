<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Color extends Field
{
    protected $css = [
        'AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css'
    ];

    protected $js = [
        'AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js'
    ];

    public function render()
    {
        $this->script = "$('#{$this->id}').colorpicker();";

        return parent::render();
    }
}
