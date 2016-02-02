<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Slider extends Field
{
    protected $options = [
        'type'  => 'single',
        'prettify' => false,
        'hasGrid' => true,
    ];

    protected $css = [
        'AdminLTE/plugins/ionslider/ion.rangeSlider.css',
        'AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css',
    ];

    protected $js = [
        'AdminLTE/plugins/ionslider/ion.rangeSlider.min.js',
    ];

    public function render()
    {
        $option = json_encode($this->options);

        $this->script = "$('#{$this->id}').ionRangeSlider($option)";

        return parent::render();
    }
}