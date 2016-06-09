<?php

namespace Encore\Admin\Grid;

class Row
{
    /**
     * Row number.
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
     * Actions of row.
     *
     * @var
     */
    protected $actions;

    /**
     * The primary key name.
     *
     * @var string
     */
    protected $keyName = 'id';

    /**
     * Action path.
     *
     * @var
     */
    protected $path;

    /**
     * Constructor
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
     * Set action path.
     *
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get action path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
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
     *
     * Set attributes of this row.
     *
     * @param array $attrs
     * @return string
     */
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

    /**
     * Set style of the row.
     *
     * @param $style
     */
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

    /**
     * Set or Get actions.
     *
     * @param string $actions
     * @return Action
     */
    public function actions($actions = 'edit|delete')
    {
        if (! is_null($this->actions)) {
            return $this->actions;
        }

        $this->actions = new Action($actions);

        $this->actions->setRow($this);

        return $this->actions;
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
     * @return $this|mixed
     */
    public function column($name, $value = null)
    {
        if (is_null($value)) {
            return array_get($this->data, $name);
        }

        if (is_callable($value)) {
            $value = $value($this->column($name));
        }

        array_set($this->data, $name, $value);

        return $this;
    }
}
