<?php

namespace Encore\Admin\Grid;

class Row
{
    /**
     * Row number.
     *
     * @var
     */
    protected $number;

    /**
     * Row data.
     *
     * @var
     */
    protected $data;

    /**
     * Attributes of row.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The primary key name.
     *
     * @var string
     */
    protected $keyName = 'id';

    /**
     * Constructor.
     *
     * @param $number
     * @param $data
     */
    public function __construct($number, $data)
    {
        $this->number = $number;

        $this->data = $data;
    }

    /**
     * Set primary key name.
     *
     * @param $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * Get id of this row.
     *
     * @return null
     */
    public function id()
    {
        return $this->__get($this->keyName);
    }

    /**
     * Get attributes in html format.
     *
     * @return string
     */
    public function getHtmlAttributes()
    {
        $attrArr = [];
        foreach ($this->attributes as $name => $val) {
            $attrArr[] = "$name=\"$val\"";
        }

        return implode(' ', $attrArr);
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return null
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Set style of the row.
     *
     * @param $style
     */
    public function style($style)
    {
        if (is_array($style)) {
            $style = implode('', array_map(function ($key, $val) {
                return "$key:$val";
            }, array_keys($style), array_values($style)));
        }

        if (is_string($style)) {
            $this->attributes['style'] = $style;
        }
    }

    /**
     * Get data of this row.
     *
     * @return mixed
     */
    public function cells()
    {
        return $this->data;
    }

    /**
     * Getter.
     *
     * @param $attr
     *
     * @return null
     */
    public function __get($attr)
    {
        return array_get($this->data, $attr);
    }

    /**
     * Get or set value of column in this row.
     *
     * @param $name
     * @param null $value
     *
     * @return $this|mixed
     */
    public function column($name, $value = null)
    {
        if (is_null($value)) {
            $column = array_get($this->data, $name);

            return $this->dump($column);
        }

        if (is_callable($value)) {
            $value = $value->bindTo($this);
            $value = $value($this->column($name));
        }

        array_set($this->data, $name, $value);

        return $this;
    }

    /**
     * Dump output column vars.
     *
     * @param mixed $var
     *
     * @return mixed|string
     */
    protected function dump($var)
    {
        if (!is_scalar($var)) {
            return '<pre>'.var_export($var, true).'</pre>';
        }

        return $var;
    }
}
