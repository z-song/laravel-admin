<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    public function render()
    {
        admin_assets('ckeditor');

        $this->script = "CKEDITOR.replace('{$this->id}');";

        return parent::render();
    }
}
