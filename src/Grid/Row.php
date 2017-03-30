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
     * Row model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

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
     * @param $model
     */
    public function __construct($number, $model)
    {
        $this->number = $number;

        $this->model = $model;
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
     * Get the value of the model's primary key.
     *
     * @return mixed
     *
     * @deprecated Use `getKey()` instead.
     */
    public function id()
    {
        return $this->getKey();
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
     * Get model of this row.
     *
     * @return mixed
     */
    public function model()
    {
        return $this->model;
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
        return $this->model->getAttribute($attr);
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
            $column = $this->model->getAttribute($name);

            return $this->dump($column);
        }

        if (is_callable($value)) {
            $value = $value->bindTo($this);
            $value = $value($this->column($name));
        }

        $this->model->{$name} = $value;

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
        if (method_exists($var, '__toString')) {
            return $var->__toString();
        }

        if (!is_scalar($var)) {
            return '<pre>'.var_export($var, true).'</pre>';
        }

        return $var;
    }
}
