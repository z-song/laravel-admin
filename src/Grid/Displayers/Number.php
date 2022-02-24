<?php

namespace Encore\Admin\Grid\Displayers;

class Number extends AbstractDisplayer
{
    /**
     * Show field as number
     *
     * @param int $decimals
     * @param string $decimal_seperator
     * @param string $thousands_seperator
     * @return Field
     */
    public function display($decimals = 0, $decimal_seperator = '.', $thousands_seperator = ',')
    {
        return number_format($this->value, $decimals, $decimal_seperator, $thousands_seperator);
    }
}
