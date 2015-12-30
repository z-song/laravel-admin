<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;

class Field {

    protected $id;

    protected $value;

    protected $label = '';

    protected $column = '';

    protected $variables = [];

    protected $options = [];

    protected $rules = '';

    public function __construct($column, $arguments = '')
    {
        $this->column = $column;

        $this->label  = isset($arguments[0]) ? $arguments[0] : ucfirst($column);

        $this->setId();
    }

    public function setId()
    {
        if(is_array($this->column)) {

            foreach($this->column as $key => $column) {
                $this->id[$key] = str_replace('.', '_', $column);
            }

        } else {
            $this->id = str_replace('.', '_', $this->column);
        }
    }

    public function fill($data)
    {
        if(is_array($this->column))
        {
            foreach($this->column as $key => $column)
            {
                $this->value[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->value = Arr::get($data, $this->column);
    }

    public function render()
    {
        if( ! empty($this->js)) {
            Admin::js($this->js);
        }

        if( ! empty($this->css)) {
            Admin::css($this->css);
        }

//        if(isset($this->view)) {
//            $view = $this->view;
//        } else {
        $class = explode('\\', get_called_class());
        $view = 'admin::form.' . strtolower(end($class));
        //}

        return view($view, $this->variables());
    }

    public function value($value)
    {
        $this->value = $value;
    }

    public function column()
    {
        return $this->column;
    }

    public function label()
    {
        return $this->label;
    }

    protected function variables()
    {
        $this->variables['id']      = $this->id;
        $this->variables['name']    = $this->fieldName($this->column);
        $this->variables['value']   = $this->value;
        $this->variables['label']   = $this->label;
        $this->variables['column']  = $this->column;

        return $this->variables;
    }

    public function fieldName($column)
    {
        if(is_string($column)) {

            $name = explode('.', $column);

            if(count($name) == 1) return $name[0];

            $html = array_shift($name);
            foreach($name as $piece) {
                $html .= "[$piece]";
            }

            return $html;
        }

        if(is_array($this->column)) {
            $names = [];
            foreach($this->column as $key => $name) {
                $names[$key] = $this->fieldName($name);
            }

            return $names;
        }
    }

    public function options($options = [])
    {
        if($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = $options;

        return $this;
    }

    public function rules($rules = null)
    {
        if(is_null($rules)) {
            return $this->rules;
        }

        $this->rules = $rules;
    }
}