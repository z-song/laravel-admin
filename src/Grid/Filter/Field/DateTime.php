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

    public function __construct($filter, $options)
    {
        $this->filter = $filter;

        $this->options = $this->checkOptions($options);

        $this->prepare();
    }

    public function prepare()
    {
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

    protected function checkOptions($options)
    {
        $options = is_array($options) ? $options : [];
        $options['format'] = isset($options['format']) ?: 'YYYY-MM-DD HH:mm:ss';
        $options['locale'] = isset($options['locale']) ?: config('app.locale');

        return $options;
    }
}
