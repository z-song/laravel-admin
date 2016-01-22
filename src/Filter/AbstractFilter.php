<?php

namespace Encore\Admin\Filter;

use Encore\Admin\Filter\Field\Text;
use Illuminate\Support\Arr;
use Encore\Admin\Filter\Field\Date;
use Encore\Admin\Filter\Field\Select;

abstract class AbstractFilter
{
    protected $label;

    protected $value;

    protected $column;

    protected $field;

    protected $query = 'where';

    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label  = $this->formatLabel($label);

        $this->setupField();
    }

    public function setupField()
    {
        $this->field = new Text();
    }

    protected function formatLabel($label)
    {
        if(empty($label)) {
            $label = ucfirst($this->column);
        }

        return str_replace('.', ' ', $label);
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
        $value = Arr::get($inputs, $this->column);

        if(is_null($value)) {
            return null;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, $this->value);
    }

    public function select($options = [])
    {
        $this->setField(new Select($options));
    }

    public function date()
    {
        $this->setField(new Date());
    }

    protected function setField($field)
    {
        $this->field = $field;
    }

    public function field()
    {
        return $this->field;
    }

    protected function buildCondition()
    {
        $column = explode('.', $this->column);

        if(count($column) == 1) {
            return [$this->query => func_get_args()];
        }

        return call_user_func_array([$this, 'buildRelationCondition'], func_get_args());
    }

    protected function buildRelationCondition()
    {
        $args = func_get_args();

        list($relation, $args[0]) = explode('.', $this->column);

        return ['whereHas' => [$relation, function($relation) use($args) {
            call_user_func_array([$relation, $this->query], $args);
        }]];
    }

    protected function variables()
    {
        $variables = [
            'name'  => $this->formatName($this->column),
            'label' => $this->label,
            'value' => $this->value,
            'field' => $this->field(),
        ];

        return array_merge($variables, $this->field()->variables());
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