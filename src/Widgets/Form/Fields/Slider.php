<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Slider extends AbstractField
{
    protected $options = [
        'type'     => 'single',
        'prettify' => false,
        'hasGrid'  => true,
    ];

    public function render()
    {
        $option = json_encode($this->options);

        $this->script = "$('#{$this->id()}').ionRangeSlider($option)";

        return parent::render();
    }
}
