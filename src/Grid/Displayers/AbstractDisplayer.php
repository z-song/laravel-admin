<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractDisplayer
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Column
     */
    protected $column;

    /**
     * @var Model
     */
    public $row;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create a new displayer instance.
     *
     * @param mixed     $value
     * @param Grid      $grid
     * @param Column    $column
     * @param \stdClass $row
     */
    public function __construct($value, Grid $grid, Column $column, $row)
    {
        $this->value = $value;
        $this->grid = $grid;
        $this->column = $column;
        $this->row = $row;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Get key of current row.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->row->{$this->grid->getKeyName()};
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        return $this->row->getAttribute($key);
    }

    /**
     * Get url path of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->grid->resource();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getColumn()->getName();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getColumn()->getClassName();
    }

    /**
     * `foo.bar.baz` => `foo[bar][baz]`.
     *
     * @return string
     */
    protected function getPayloadName($name = '')
    {
        $keys = collect(explode('.', $name ?: $this->getName()));

        return $keys->shift().$keys->reduce(function ($carry, $val) {
            return $carry."[$val]";
        });
    }

    /**
     * Get translation.
     *
     * @param string $text
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function trans($text)
    {
        return trans("admin.$text");
    }

    /**
     * Display method.
     *
     * @return mixed
     */
    abstract public function display();
}
