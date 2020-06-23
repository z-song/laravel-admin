<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Textarea extends AbstractDisplayer
{
    public function display($rows = 5)
    {
        $name = $this->column->getName();

        return Admin::component('admin::grid.inline-edit.textarea', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'name'     => $name,
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$name}",
            'target'   => "ie-content-{$name}-{$this->getKey()}",
            'rows'     => $rows,
        ]);
    }
}
