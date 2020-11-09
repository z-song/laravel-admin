<?php

namespace Encore\Admin;

use Encore\Admin\Form\Field;
use Encore\Admin\Form\Layout\Row;

abstract class AbstractForm
{
    /**
     * Field rows in form.
     *
     * @var array
     */
    protected $rows = [];

    /**
     * @var bool
     */
    protected $horizontal = false;

    /**
     * @return bool
     */
    public function horizontal()
    {
        $this->horizontal = true;
    }

    /**
     * @return bool
     */
    public function isHorizontal()
    {
        return $this->horizontal;
    }

    /**
     * Add a row in form.
     *
     * @param Closure $callback
     *
     * @return Row
     */
    public function row(\Closure $callback = null)
    {
        return $this->rows[] = new Row($this, $callback);
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        $field = $this->resolveField($method, $arguments);

        if (!$field instanceof Field) {
            return $field;
        }

        $this->row()->column()->addField($field);

        return $field;
    }

    public abstract function fields();

    public abstract function resolveField($method, $arguments = []);
}
