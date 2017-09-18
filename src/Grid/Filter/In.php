<?php

namespace Encore\Admin\Grid\Filter;

class In extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected $query = 'whereIn';

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (is_null($value)) {
            return;
        }

        $this->value = (array) $value;

        return $this->buildCondition($this->column, $this->value);
    }
}
