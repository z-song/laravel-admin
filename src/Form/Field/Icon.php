<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fas fa-pencil-alt';

    protected static $css = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').iconpicker({placement:'bottomLeft'});

EOT;

        $this->prepend('<i class="fas fa-pencil-alt"></i>')
            ->defaultAttribute('style', 'width: 140px');

        return parent::render();
    }
}
