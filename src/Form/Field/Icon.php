<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fa-pencil';

    public function render()
    {
        $this->script = <<<SCRIPT
$('{$this->getElementClassSelector()}').iconpicker({placement:'bottomLeft'});
SCRIPT;

        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('style', 'width: 140px');

        admin_assets('iconpicker');

        return parent::render();
    }
}
