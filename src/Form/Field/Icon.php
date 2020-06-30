<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fa-pencil';

    public function render()
    {
        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').iconpicker({placement:'bottomLeft'});

EOT;

        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('style', 'width: 140px');

        admin_require('iconpicker');

        return parent::render();
    }
}
