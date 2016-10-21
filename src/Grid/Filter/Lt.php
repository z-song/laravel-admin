<?php

namespace Encore\Admin\Grid\Filter;

class Lt extends AbstractFilter
{
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (is_null($value)) {
            return null;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, '<=', $this->value);
    }
}
