<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class Inline extends Field
{
    protected $fields = [];

    protected $view = 'admin::form.inline';

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function addField(Field $field)
    {
        $column = $field->column();

        $key = is_array($column) ? join(',', $column) : $column;

        $this->fields[$key] = $field;

        $field->setAsInline();

        return $this;
    }

    public function render()
    {
        $this->addVariables([
            'fields' => $this->fields,
        ]);

        return parent::render();
    }
}
