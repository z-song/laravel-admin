<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Ip extends Field
{
    protected $rules = 'ip';

    protected $js = [
        'AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}