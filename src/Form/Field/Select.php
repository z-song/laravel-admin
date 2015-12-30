<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class Select extends Field
{
    protected $css = [
        'AdminLTE/plugins/select2/select2.min.css'
    ];

    protected $js = [
        'AdminLTE/plugins/select2/select2.full.min.js'
    ];

    public function render()
    {
        Admin::script("$(\"#{$this->id}\").select2();");

        return parent::render()->with(['options' => $this->options]);
    }
}