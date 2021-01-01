<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

class Textarea extends AbstractDisplayer
{
    public function display($rows = 5)
    {
        return Admin::view('admin::table.inline-edit.textarea', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'rows'     => $rows,
        ]);
    }
}
