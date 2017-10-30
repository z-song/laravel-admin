<?php

namespace Encore\Admin\Form\Field;

class Number extends Text
{
    protected static $js = [
        '/packages/admin/number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $this->default((int) $this->default);
        $options = array_merge([
            'upClass'   => 'success',
            'downClass' => 'primary',
            'center'    => true, ], $this->options);
        $options = json_encode($options);
        $this->script = <<<EOT

$('{$this->getElementClassSelector()}:not(.initialized)')
    .addClass('initialized')
    .bootstrapNumber({$options});

EOT;

        $this->prepend('')->defaultAttribute('style', 'width: 100px');

        return parent::render();
    }
}
