<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Number extends Field
{
    protected static $js = [
        '/packages/admin/number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $this->default((int)$this->default);

        $this->script = <<<EOT

$('.{$this->getElementClass()}').bootstrapNumber({
	upClass: 'success',
	downClass: 'primary',
	center: true
});

EOT;

        return parent::render();
    }
}
