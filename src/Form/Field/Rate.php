<?php

namespace Encore\Admin\Form\Field;

class Rate extends Text
{
    public function render()
    {
        $this->prepend('')
            ->append('<span class="input-group-text">%</span>')
            ->defaultAttribute('style', 'text-align:right;')
            ->defaultAttribute('placeholder', 0);

        return parent::render();
    }
}
