<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Slider extends DataField
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

        $this->script = "$('.{$this->getElementClass()}').ionRangeSlider($option)";

        return parent::render();
    }
}
