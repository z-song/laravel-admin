<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Decimal extends DataField
{
    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function render()
    {
        $this->script = "$('.{$this->getElementClass()}').inputmask('decimal', {
    rightAlign: true
  });";

        return parent::render();
    }
}
