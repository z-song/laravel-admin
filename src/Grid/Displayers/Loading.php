<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Support\Arr;

class Loading extends AbstractDisplayer
{
    public function display($values = [], $others = [])
    {
        $values = (array) $values;

        if (in_array($this->value, $values)) {
            return '<i class="fa fa-refresh fa-spin text-primary"></i>';
        }

        return Arr::get($others, $this->value, $this->value);
    }
}
