<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
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
        Admin::script("$('.{$this->column}').colorpicker();");

        return parent::render();
    }
}