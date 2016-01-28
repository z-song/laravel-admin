<?php

namespace Encore\Admin\Grid;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

class Column {

    protected $name;

    protected $label;

    protected $sortable = false;

    protected $sort;

    protected $attributes = [];

    protected $valueWrapper;

    protected $relation = false;

    protected $relationColumn;

    public function __construct($name, $label)
    {
        $this->name = $name;

        $this->label =  $this->formatLabel($label);
    }

    public function getName()
    {
        return $this->name;
    }

    protected function formatLabel($label)
    {
        $label = $label ?: ucfirst($this->name);

        return str_replace(['.', '_'], ' ', $label);
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

    public function isRelation()
    {
        return (bool) $this->relation;
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

    /**
     * Mark this column as sortable.
     *
     * @return void
     */
    public function sortable()
    {
        $this->sortable = true;
    }

    /**
     * Create the column sorter.
     *
     * @return string|void
     */
    public function sorter()
    {
        if(! $this->sortable) return;

        $icon = 'fa-sort';
        $type = 'desc';

        if($this->isSorted()) {
            $type = $this->sort['type'] == 'desc' ? 'asc' : 'desc';
            $icon .= "-amount-{$this->sort['type']}";
        }

        app('request')->merge(['_sort' => ['column' => $this->name, 'type' => $type]]);

        $url = Url::current() . '?' . http_build_query(app('request')->all());

        return "<a class=\"fa fa-fw $icon\" href=\"$url\"></a>";
    }

    /**
     * Determine if this column is currently sorted.
     *
     * @return bool
     */
    public function isSorted()
    {
        $this->sort = app('request')->get('_sort');

        if(empty($this->sort)) return false;

        return isset($this->sort['column']) && $this->sort['column'] == $this->name;
    }

    /**
     * @param string  $method
     * @param array   $arguments
     * @return $this
     */
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