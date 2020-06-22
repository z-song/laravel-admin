<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Radio extends AbstractDisplayer
{
    public function display($options = [])
    {
        $name = $this->column->getName();

        return Admin::component('admin::grid.inline-edit.radio', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'name'     => $name,
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$name}",
            'target'   => "ie-content-{$name}-{$this->getKey()}",
            
            'options'  => $options,
        ]);
    }
}
