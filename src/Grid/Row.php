<?php

namespace Encore\Admin\Grid;

use Illuminate\Support\Arr;

class Row
{

    protected $number;

    protected $data;

    protected $attributes = [];

    public function __construct($number, $data)
    {
        $this->number = $number;

        $this->data = $data;
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

    public function cells()
    {
        return $this->data;
    }

    public function __get($attr)
    {
        return $this->data[$attr] ?: null;
    }

    public function column($name)
    {
        return Arr::get($this->data, $name);
    }
}
