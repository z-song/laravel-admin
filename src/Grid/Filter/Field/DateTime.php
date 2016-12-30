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

    public function __construct($filter, array $options = [])
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
        $options['format'] = array_get($options, 'format', 'YYYY-MM-DD HH:mm:ss');
        $options['locale'] = array_get($options, 'locale', config('app.locale'));

        return $options;
    }
}
