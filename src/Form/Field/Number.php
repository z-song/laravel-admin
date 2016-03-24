<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Number extends Field
{
    protected $js = [
        'number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $this->default(0);

        $this->script = <<<EOT

//success/primary/danger/warning/default

$('#{$this->id}').bootstrapNumber({
	upClass: 'success',
	downClass: 'primary',
	center: true
});

EOT;

        return parent::render();
    }
}