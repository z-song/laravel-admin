<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Checkbox extends Field
{
    protected $values;

    protected static $css = [
        '/packages/admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        'packages/admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    public function fill($data)
    {
        $relations = array_get($data, $this->column);

        foreach ($relations as $relation) {
            $this->value[] = array_pop($relation['pivot']);
        }
    }

    public function setOriginal($data)
    {
        $relations = array_get($data, $this->column);

        foreach ($relations as $relation) {
            $this->original[] = array_pop($relation['pivot']);
        }
    }

    public function render()
    {
        $this->options['checkboxClass'] = 'icheckbox_minimal-blue';

        $this->script = "$('.{$this->column}').iCheck(".json_encode($this->options).');';

        return parent::render()->with(['values' => $this->values]);
    }

    public function values($values)
    {
        $this->values = $values;

        return $this;
    }

    public function prepare($value)
    {
        return array_filter($value);
    }
}
