<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Input extends AbstractDisplayer
{
    public function display($mask = '')
    {
        $name = $this->column->getName();

        return Admin::component('admin::grid.inline-edit.input', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'name'     => $name,
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$name}",
            'target'   => "ie-content-{$name}-{$this->getKey()}",
            'mask'     => $mask,
        ]);
    }
}
