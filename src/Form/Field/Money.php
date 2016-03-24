<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Money extends Field
{
    protected $js = [
        'AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    protected $symbol = '$';

    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function render()
    {
        $this->script = <<<EOT

$('#{$this->id}').inputmask("currency", {radixPoint: '.', prefix:''})

EOT;

        return parent::render()->with(['symbol' => $this->symbol]);
    }
}