<?php

namespace Encore\Admin\Grid\Filter\Field;

use Encore\Admin\Admin;

class DateTime
{
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
