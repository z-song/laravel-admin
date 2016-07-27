<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Ip extends Field
{
    protected $rules = 'ip';

    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}
