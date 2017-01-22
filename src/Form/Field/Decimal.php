<?php

namespace Encore\Admin\Form\Field;

class Decimal extends Text
{
    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function render()
    {
        $this->script = "$('.{$this->getElementClass()}').inputmask('decimal', { rightAlign: true});";

        $this->prepend('<i class="fa fa-terminal"></i>')
            ->defaultAttribute('style', 'width: 130px');

        return parent::render();
    }
}
