<?php

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;

/**
 * @mixin Form
 */
class Column
{
    /**
     * @var Collection
     */
    protected $fields = [];

    /**
     * @var int
     */
    protected $width;

    /**
     * @var Form|\Encore\Admin\Widgets\Form
     */
    protected $form;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * Column constructor.
     *
     * @param int $width
     */
    public function __construct($width = 12, $form, $callback = null)
    {
        if ($width < 1) {
            $this->width = intval(12 * $width);
        } elseif ($width == 1) {
            $this->width = 12;
        } else {
            $this->width = $width;
        }

        $this->form = $form;
        $this->callback = $callback;

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }
    }

    /**
     * Get all filters in this column.
     *
     * @return Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function width()
    {
        if ($this->width == 12) {
            return 'col';
        }

        return "col-{$this->width}";
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments = [])
    {
        return $this->fields[] = call_user_func_array(
            [$this->form, 'resolveField'], [$method, $arguments]
        );
    }
}
