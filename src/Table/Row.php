<?php

namespace Encore\Admin\Table;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;

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
     * @var mixed
     */
    protected $key;

    /**
     * Row constructor.
     * @param mixed $number
     * @param array $data
     * @param mixed $key
     */
    public function __construct($number, $data, $key)
    {
        $this->data = $data;
        $this->number = $number;
        $this->key = $key;

        $this->attributes = [
            'data-key' => $key,
        ];
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get attributes in html format.
     *
     * @return string
     */
    public function getRowAttributes()
    {
        return admin_attrs($this->attributes);
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
        if ($attributes = Column::getAttributes($column, $this->getKey())) {
            return admin_attrs($attributes);
        }

        return '';
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * Set style of the row.
     *
     * @param array|string $style
     */
    public function style($style)
    {
        if (is_array($style)) {
            $style = implode(';', array_map(function ($key, $val) {
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
     * @param mixed $attr
     *
     * @return mixed
     */
    public function __get($attr)
    {
        return Arr::get($this->data, $attr);
    }

    /**
     * Get or set value of column in this row.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this|mixed
     */
    public function column($name, $value = null)
    {
        if (is_null($value)) {
            $column = Arr::get($this->data, $name);

            return $this->output($column);
        }

        if ($value instanceof Closure) {
            $value = $value->call($this, $this->column($name));
        }

        Arr::set($this->data, $name, $value);

        return $this;
    }

    /**
     * Output column value.
     *
     * @param mixed $value
     *
     * @return mixed|string
     */
    protected function output($value)
    {
        if ($value instanceof Renderable) {
            $value = $value->render();
        }

        if ($value instanceof Htmlable) {
            $value = $value->toHtml();
        }

        if ($value instanceof Jsonable) {
            $value = $value->toJson();
        }

        if (!is_null($value) && !is_scalar($value)) {
            return sprintf('<pre><code>%s</code></pre>', var_export($value, true));
        }

        return $value;
    }
}
