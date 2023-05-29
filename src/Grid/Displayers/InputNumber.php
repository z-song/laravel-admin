<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class InputNumber extends Input
{
    public function display($number2persian = true)
    {
        Admin::js('/vendor/laravel-admin/num2persian/dist/num2persian.min.js');

        return Admin::component('admin::grid.inline-edit.number', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'number2persian' => $number2persian,
        ]);
    }
}
