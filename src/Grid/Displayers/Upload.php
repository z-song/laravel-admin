<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Upload extends AbstractDisplayer
{
    public function display($multiple = false)
    {
        return Admin::component('admin::components.grid-inline-upload', [
            'key'      => $this->getKey(),
            'name'     => $this->column->getName(),
            'value'    => $this->getValue(),
            'target'   => "inline-upload-{$this->getKey()}",
            'resource' => $this->getResource(),
            'multiple' => $multiple,
        ]);
    }
}
