<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class Radio extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Admin::view('admin::table.inline-edit.radio', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'value'    => $this->getValue(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'display'  => Arr::get($options, $this->getValue(), ''),
            'options'  => $options,
        ]);
    }
}
