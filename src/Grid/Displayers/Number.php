<?php

namespace Encore\Admin\Grid\Displayers;

class Number extends AbstractDisplayer
{
    /**
     * Show field as number
     *
     * @param int $decimals
     * @param string $decimal_separator
     * @param string $thousands_separator
     * @return Field
     */
    public function display($decimals = 0, $decimal_separator = '.', $thousands_separator = ',')
    {
        return number_format($this->value, $decimals, $decimal_separator, $thousands_separator);
    }
}
