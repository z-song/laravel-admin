<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Number extends AbstractField
{
    public function render()
    {
        $this->default(0);

        $this->script = <<<EOT

$('#{$this->id()}').bootstrapNumber({
	upClass: 'success',
	downClass: 'primary',
	center: true
});

EOT;

        return parent::render();
    }
}
