<?php

namespace Encore\Admin\Form;

use Encore\Admin\AbstractForm;
use Encore\Admin\Form;

class TabItem extends AbstractForm
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

    public function fields()
    {
        return $this->form->fields();
    }

    public function resolveField($method, $arguments = [])
    {
        return $this->form->resolveField($method, $arguments);
    }
}

