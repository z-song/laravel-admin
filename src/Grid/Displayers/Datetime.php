<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Datetime extends AbstractDisplayer
{
    public function display($format = '')
    {
        return Admin::component('admin::components.grid-inline-datetime', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'format'   => $format,
            'name'     => $this->column->getName(),
            'resource' => $this->getResource(),
            'locale'   => config('app.locale'),
        ]);
    }
}
