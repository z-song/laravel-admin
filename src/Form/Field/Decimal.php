<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Decimal extends Field
{
    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function render()
    {
        $this->script = "$('#{$this->id}').inputmask('decimal', {
    rightAlign: true
  });";

        return parent::render();
    }
}
