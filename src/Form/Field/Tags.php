<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class Tags extends Field
{
    protected $value = [];

    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js',
    ];

    public function fill($data)
    {
        $this->value = array_get($data, $this->column);

        if (is_string($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $this->value = array_filter((array) $this->value);
    }

    public function prepare($value)
    {
        if (is_array($value) && !Arr::isAssoc($value)) {
            $value = implode(',', array_filter($value));
        }

        return $value;
    }

    public function value($value = null)
    {
        if (is_null($value)) {
            return empty($this->value) ? $this->getDefault() : $this->value;
        }

        $this->value = $value;

        return $this;
    }

    public function render()
    {
        $this->script = "$(\"{$this->getElementClassSelector()}\").select2({
            tags: true,
            tokenSeparators: [',']
        });";

        return parent::render();
    }
}
