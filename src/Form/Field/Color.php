<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Color extends Field
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js',
    ];

    public function render()
    {
        $this->script = "$('#{$this->id}').colorpicker();";

        return parent::render();
    }
}
