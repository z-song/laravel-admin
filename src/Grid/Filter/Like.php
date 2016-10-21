<?php

namespace Encore\Admin\Grid\Filter;

class Like extends AbstractFilter
{
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (is_null($value)) {
            return;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, 'like', "%{$this->value}%");
    }
}
