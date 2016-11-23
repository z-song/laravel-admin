<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Mobile extends Field
{
    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    protected $format = '99999999999';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function render()
    {
        $options = json_encode(['mask' => $this->format]);

        $this->script = <<<EOT

$('#{$this->id}').inputmask($options);
EOT;

        return parent::render();
    }
}
