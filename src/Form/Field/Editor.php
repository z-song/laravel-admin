<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    public function render()
    {
        $this->script = "CKEDITOR.replace('{$this->column}');";

        return parent::render();
    }
}
