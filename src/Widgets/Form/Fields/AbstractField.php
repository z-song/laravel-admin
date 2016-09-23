<?php

namespace Encore\Admin\Widgets\Form\Fields;

use Encore\Admin\Facades\Admin;
use Illuminate\Contracts\Support\Renderable;

abstract class AbstractField implements Renderable
{
    protected $name = '';

    protected $label = '';

    protected $placeholder;

    protected $id = '';

    protected $script = '';

    protected $default = '';

    public function __construct($name, $arguments)
    {
        $this->name  = $name;

        $this->label = array_get($arguments, 0, ucfirst($name));
    }

    public function label()
    {
        return $this->label;
    }

    public function name()
    {
        return $this->name;
    }

    public function placeholder()
    {
        return $this->placeholder;
    }

    public function id()
    {
        return $this->id ?: $this->name();
    }

    public function variables()
    {
        return [
            'id'        => $this->id(),
            'label'     => $this->label(),
            'name'      => $this->name(),
            'column'    => '',
            'value'     => $this->default,
        ];
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView()
    {
        $class = explode('\\', get_called_class());

        return 'admin::form.'.strtolower(end($class));
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        if (! empty($this->script)) {
            Admin::script($this->script);
        }

        return view($this->getView(), $this->variables());
    }

    public function __call($method, $arguments)
    {
        if ($method === 'default') {
            $this->default = $arguments[0];

            return $this;
        }
    }
}
