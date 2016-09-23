<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Ip extends AbstractField
{
    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}
