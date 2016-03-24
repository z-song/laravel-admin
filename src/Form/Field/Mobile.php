<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Mobile extends Field
{
    protected $js = [
        'AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    protected $format = '';

    public function format($format = '999 9999 9999')
    {
        $this->format = $format;
    }

    public function render()
    {
        if(empty($this->format)) $this->format();

        $options = json_encode([
            'mask' => $this->format
        ]);

        $this->script = <<<EOT

$('#{$this->id}').inputmask($options);
EOT;

        return parent::render();
    }
}