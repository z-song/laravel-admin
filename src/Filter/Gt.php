<?php

namespace Encore\Admin\Filter;

class Gt extends AbstractFilter
{
    public function condition($inputs)
    {
        if (! isset($inputs[$this->column])) {
            return;
        }

        $this->value = $inputs[$this->column];

        return $this->buildCondition($this->column, '>=', $this->value);
    }
}
