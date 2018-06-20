<?php

namespace Encore\Admin\Grid;

class Row
{
    /**
     * Row number.
     *
     * @var
     */
    public $number;

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
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->model->getKey();
    }

    /**
     * Get attributes in html format.
     *
     * @return string
     */
    public function getRowAttributes()
    {
        return $this->formatHtmlAttribute($this->attributes);
    }

    /**
     * Get column attributes.
     *
     * @param string $column
     *
     * @return string
     */
    public function getColumnAttributes($column)
    {
        if ($attributes = Column::getAttributes($column)) {
            return $this->formatHtmlAttribute($attributes);
        }

        return '';
    }

    /**
     * Format attributes to html.
     *
     * @param array $attributes
     *
     * @return string
     */
    private function formatHtmlAttribute($attributes = [])
    {
        $attrArr = [];
        foreach ($attributes as $name => $val) {
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
    public function model()
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

        if ($value instanceof \Closure) {
            $value = $value->call($this, $this->column($name));
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
