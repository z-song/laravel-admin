<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class Checkbox extends Field
{
    protected $values;

    protected $css = [
        'AdminLTE/plugins/iCheck/all.css'
    ];

    protected $js = [
        'AdminLTE/plugins/iCheck/icheck.min.js'
    ];

    public function render()
    {
        $this->options['checkboxClass'] = 'icheckbox_minimal-blue';

        Admin::script("$('.{$this->column}').iCheck(". json_encode($this->options) .");");

        return  parent::render()->with(['values' => $this->values]);
    }

    public function values($values)
    {
        $this->values = $values;
    }
}