<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Slider extends Field
{
    protected $options = [
        'type'      => 'single',
        'prettify'  => false,
        'hasTable'  => true,
    ];

    public function render()
    {
        $this->addVariables(['options' => $this->options]);

        return parent::render();
    }
}
