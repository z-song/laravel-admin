<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Number extends Field
{
    public function render()
    {
        $this->default(0);

        $this->script = <<<EOT

$('#{$this->id}').bootstrapNumber({
	upClass: 'success',
	downClass: 'primary',
	center: true
});

EOT;

        return parent::render();
    }
}
