<?php

namespace Encore\Admin\Grid\Filter;

class Like extends AbstractFilter
{
    /**
     * @var string
     */
    protected $exprFormat = '%{value}%';

    /**
     * @var string
     */
    protected $operator = 'like';

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|void
     */
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (is_array($value)) {
            $value = array_filter($value);
        }

        if (is_null($value) || empty($value)) {
            return;
        }

        $this->value = $value;

        $expr = str_replace('{value}', $this->value, $this->exprFormat);

        return $this->buildCondition($this->column, $this->operator, $expr);
    }
}
