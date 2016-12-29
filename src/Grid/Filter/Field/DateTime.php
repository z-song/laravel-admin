<?php

namespace Encore\Admin\Grid\Filter\Field;

use Encore\Admin\Admin;

class DateTime
{
    /**
     * @var \Encore\Admin\Grid\Filter\AbstractFilter
     */
    protected $filter;

    protected $options = [];

    public function __construct($filter, $options = [])
    {
        $this->filter = $filter;

        $this->options = $options;

        $this->prepare();
    }

    public function prepare()
    {
        $this->options['format'] = $this->options['format'] || 'YYYY-MM-DD HH:mm:ss';
        $this->options['locale'] = $this->options['locale'] || config('app.locale');

        $script = "$('#{$this->filter->getId()}').datetimepicker(".json_encode($this->options).');';

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
