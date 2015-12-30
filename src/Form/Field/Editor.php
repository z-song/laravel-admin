<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class Editor extends Field
{
    protected $js = [
        'https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js'
    ];

    public function render()
    {
        Admin::script("CKEDITOR.replace('{$this->column}');");

        return parent::render();
    }
}