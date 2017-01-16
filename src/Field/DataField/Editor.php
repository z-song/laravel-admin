<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Editor extends DataField
{
    protected static $js = [
        '//cdn.ckeditor.com/4.5.10/standard/ckeditor.js',
    ];

    public function render()
    {
        $this->script = "CKEDITOR.replace('{$this->column}');";

        return parent::render();
    }
}
