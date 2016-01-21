<?php

namespace Encore\Admin\Filter;

abstract class AbstractFiled
{
    protected $column;

    protected $label;

    protected $value;

    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label  = $this->formatLabel($label);
    }

    protected function formatLabel($label)
    {
        if(empty($label)) {
            $label = ucfirst($this->column);
        }

        $label = explode('.', $label);

        return join(' ', $label);
    }

    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if(count($columns) == 1) return $columns[0];

        $name = array_shift($columns);
        foreach($columns as $column) {
            $name .= "[$column]";
        }

        return $name;
    }

    public function condition($inputs)
    {
        if(! isset($inputs[$this->column]) || empty($inputs[$this->column])) {
            return;
        }

        $this->value = $inputs[$this->column];

        return $this->buildCondition('');
    }

    protected function buildCondition($format = '')
    {
        $column = explode('.', $this->column);

        if(count($column) == 1) {
            return ['where' => [$this->column, $this->value]];
        }

        list($relation, $column) = $column;

        return ['whereHas' => [$relation, function($relation) use($column) {
            $relation->where($column, $this->value);
        }]];
    }

    protected function variables()
    {
        return [
            'name'  => $this->formatName($this->column),
            'label' => $this->label,
            'value' => $this->value,
        ];
    }

    public function render()
    {
        $class = explode('\\', get_called_class());
        $view = 'admin::filter.' . strtolower(end($class));

        return view($view, $this->variables());
    }

    public function __toString()
    {
        return $this->render();
    }
}