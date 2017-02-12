<?php

namespace Encore\Admin\Form\Field;

class MultipleSelect extends Select
{

    /**
     * Pivot column name.
     *
     * @var string
     */
    protected $pivotColumn = '';

    public function __construct($column, $arguments = [])
    {
        if (isset($arguments[1])) {
            parent::__construct($column, array($arguments[1]));
            $this->pivotColumn = $arguments[0];
        } else {
            parent::__construct($column, $arguments);
        }
    }

    public function fill($data)
    {
        $relations = array_get($data, $this->column);

        if (is_string($relations)) {
            $this->value = explode(',', $relations);
        }

        if (is_array($relations)) {
            if (is_string(current($relations))) {
                $this->value = $relations;
            } else {
                foreach ($relations as $relation) {
                    if (!empty($this->pivotColumn)) {
                        $this->value[] = $relation['pivot'][$this->pivotColumn];
                    } else {
                        $this->value[] = array_pop($relation['pivot']);
                    }
                }
            }
        }
    }

    public function setOriginal($data)
    {
        $relations = array_get($data, $this->column);

        if (is_string($relations)) {
            $this->original = explode(',', $relations);
        }

        if (is_array($relations)) {
            if (is_string(current($relations))) {
                $this->original = $relations;
            } else {
                foreach ($relations as $relation) {
                    if (!empty($this->pivotColumn)) {
                        $this->value[] = $relation['pivot'][$this->pivotColumn];
                    } else {
                        $this->value[] = array_pop($relation['pivot']);
                    }
                }
            }
        }
    }

    public function prepare(array $value)
    {
        return array_filter($value);
    }
}
