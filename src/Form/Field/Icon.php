<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'far fa-circle';

    protected $view = 'admin::form.iconpicker';

    public $bePrepend = false;

    public function renderPrepend()
    {
        $this->view = 'admin::form.iconpicker-prepend';

        return parent::render();
    }

    public function render()
    {
        if ($this->bePrepend) {
            return '';
        }

        return parent::render();
    }
}
