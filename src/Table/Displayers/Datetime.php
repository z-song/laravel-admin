<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

class Datetime extends AbstractDisplayer
{
    public function display($format = '')
    {
        return Admin::view('admin::table.inline-edit.datetime', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",

            'format'   => $format,
            'locale'   => config('app.locale'),
        ]);
    }
}
