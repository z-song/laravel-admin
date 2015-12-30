<?php

namespace Encore\Admin\Grid;

use Closure;
use Illuminate\Support\Arr;

class Column {

    protected $name;

    protected $label;

    protected $sortable = false;

    protected $attributes = [];

    protected $valueWrapper;

    protected $dataSet = [];

    protected $relation = false;

    protected $relationColumn;

    public function __construct($name, $label)
    {
        $this->name = $name;

        $this->label = $label ?: $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function value(Closure $callable)
    {
        $this->valueWrapper = $callable;

        return $this;
    }

    public function hasValueWrapper()
    {
        return (bool) $this->valueWrapper;
    }

    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    public function map($data)
    {
        foreach($data as &$item) {
            $value = Arr::get($item, $this->name);

            if($this->hasValueWrapper())
            {
                $value = call_user_func($this->valueWrapper, $value);
                Arr::set($item, $this->name, $value);
            }
        }

        return $data;
    }

    public function sortable()
    {
        $this->sortable = true;
    }

    public function isRelation()
    {
        return (bool) $this->relation;
    }

    public function __call($method, $arguments)
    {
        if($this->isRelation()) {
            $this->name = "{$this->relation}.$method";
            $this->label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

            $this->relationColumn = $method;

            return $this;
        }
    }
}