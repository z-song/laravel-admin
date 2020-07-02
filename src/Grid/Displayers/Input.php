<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Input extends AbstractDisplayer
{
    public function display($mask = '')
    {
        if ($mask) {
            admin_assets('inputmask');
        }

        return Admin::view('admin::grid.inline-edit.input', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'mask'     => $mask,
        ]);
    }
}
