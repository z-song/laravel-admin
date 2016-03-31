<?php

namespace Encore\Admin\Filter\Field;

use Encore\Admin\Admin;

class DateTime
{
    protected $css = [
        'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'
    ];

    protected $js = [
        'moment/min/moment-with-locales.min.js',
        'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ];

    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;

        $this->prepare();
    }

    public function prepare()
    {
        $options['format'] = 'YYYY-MM-DD HH:mm:ss';
        $options['locale'] = config('app.locale');

        $script = "$('#{$this->filter->getId()}').datetimepicker(". json_encode($options) .");";

        Admin::js($this->js);
        Admin::css($this->css);
        Admin::script($script);
    }

    public function variables()
    {
        return [];
    }

    public function name()
    {
        return 'datetime';
    }
}
