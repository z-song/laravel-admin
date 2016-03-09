<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Date extends Field
{
    protected $format = 'YYYY-MM-DD';

    protected $css = [
        'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'
    ];

    protected $js = [
        'moment/min/moment-with-locales.min.js',
        'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ];

    public function render()
    {
        $this->options['format'] = $this->format;
        $this->options['locale'] = config('app.locale');

        $this->script = "$('#{$this->id}').datetimepicker(". json_encode($this->options) .");";

        return parent::render();
    }
}
