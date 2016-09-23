<?php

namespace Encore\Admin\Widgets\Form;

use Encore\Admin\Widgets\Form\Fields\AbstractField;
use Encore\Admin\Widgets\Form\Fields\FieldAbstract;
use Illuminate\Contracts\Support\Renderable;

class Form implements Renderable
{
    protected $id = '';

    protected $action = '/';

    protected $method = 'POST';

    protected $fields = [];

    public function __construct($id = '', $name = '')
    {
        $this->id = $id ?: 'form_id_'.uniqid();
        $this->name = $name ?: 'form_name_'.uniqid();
    }

    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    public function method($method = 'POST')
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field|void
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $column = array_get($arguments, 0, ''); //[0];

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
    }

    public static function findFieldClass($method)
    {
        $className = __NAMESPACE__.'\\Fields\\'.ucfirst($method);

        if (class_exists($className)) {
            return $className;
        }

        if ($method == 'switch') {
            return __NAMESPACE__.'\\Form\\Field\\SwitchField';
        }

        return false;
    }


    protected function pushField(AbstractField &$field)
    {
        array_push($this->fields, $field);

        return $this;
    }

    protected function getVariables()
    {
        return [
            'fields' => $this->fields,
            'action' => $this->action,
            'method' => $this->method,
        ];
    }

    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.form', $this->getVariables());
    }

    public function __toString()
    {
        return $this->render();
    }
}
