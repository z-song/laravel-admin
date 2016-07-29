<?php

namespace Encore\Admin\Grid\Filter;

class Like extends AbstractFilter
{
    public function condition($inputs)
    {
        if (!isset($inputs[$this->column])) {
            return;
        }

        $this->value = $inputs[$this->column];

        return $this->buildCondition($this->column, 'like', "%{$this->value}%");
    }
}
