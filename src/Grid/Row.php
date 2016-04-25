<?php

namespace Encore\Admin\Grid;

use Illuminate\Support\Arr;

class Row
{
    protected $number;

    protected $data;

    protected $attributes = [];

    protected $actions;

    protected $keyName = 'id';

    public function __construct($number, $data)
    {
        $this->number = $number;

        $this->data = $data;
    }

    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    public function id()
    {
        return $this->__get($this->keyName);
    }

    public function attrs(array $attrs = [])
    {
        if (empty($attrs)) {

            $attrArr = [];
            foreach ($this->attributes as $name => $val) {
                $attrArr[] = "$name=\"$val\"";
            }

            return join(' ', $attrArr);
        }

        $this->attributes = $attrs;
    }

    public function style($style)
    {
        if (is_array($style)) {
            $style = join('', array_map(function ($key, $val) {
                return "$key:$val";
            }, array_keys($style), array_values($style)));
        }

        if (is_string($style)) {
            $this->attributes['style'] = $style;
        }
    }

    public function actions($actions = 'edit|delete')
    {
        if (! is_null($this->actions)) {
            return $this->actions;
        }

        $this->actions = new Action($actions);

        $this->actions->setRow($this);

        return $this->actions;
    }

    public function cells()
    {
        return $this->data;
    }

    public function __get($attr)
    {
        return $this->data[$attr] ?: null;
    }

    public function column($name, $value = null)
    {
        if (is_null($value)) {
            return Arr::get($this->data, $name);
        }

        if (is_callable($value)) {
            $value = $value($this->column($name));
        }

        Arr::set($this->data, $name, $value);

        return $this;
    }
}
