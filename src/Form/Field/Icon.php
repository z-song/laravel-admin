<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fa-pencil';

    protected static $css = [
        '/packages/admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').iconpicker({placement:'bottomLeft'});

EOT;

        $this->prepend('<i class="fa fa-pencil"></i>')
            ->defaultAttribute('style', 'width: 140px');

        return parent::render();
    }
}
