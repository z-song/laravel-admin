<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Slider extends Field
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.css',
        '/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css',
    ];

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.min.js',
    ];

    protected $options = [
        'type'     => 'single',
        'prettify' => false,
        'hasGrid'  => true,
    ];

    public function render()
    {
        $option = json_encode($this->options);

        $this->script = "$('{$this->getElementClassSelector()}').ionRangeSlider($option)";

        return parent::render();
    }
}
