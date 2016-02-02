<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Ip extends Field
{
    protected $rules = 'ip';

    protected $js = [
        'AdminLTE/plugins/input-mask/jquery.inputmask.js',
        'AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js',
        'AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js'
    ];

    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}