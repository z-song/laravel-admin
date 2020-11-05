<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Encore\Admin\Form\Layout\Row;

class TabItem
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var bool
     */
    public $active = false;

    /**
     * @var array
     */
    public $rows = [];

    /**
     * @var Form
     */
    protected $form;

    /**
     * TabItem constructor.
     * @param string $title
     * @param Form $form
     * @param \Closure $callback
     * @param bool $active
     */
    public function  __construct($title, $form, $callback, $active = false)
    {
        $this->title = $title;
        $this->form = $form;
        $this->active = $active;

        $this->id = uniqid('fomr-tab-');

        if ($callback) {
            $callback($this);
        }
    }

    /**
     * @param \Closure|null $callback
     * @return Row
     */
    public function row(\Closure $callback = null)
    {
        return $this->rows[] = new Row($this->form, $callback);
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return Field\Nullable|mixed
     */
    public function __call($method, $arguments = [])
    {
        $field = $this->form->resolveField($method, $arguments);

        $this->row()->column()->addField($field);

        return $field;
    }
}

