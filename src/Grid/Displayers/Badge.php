<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;

class Badge extends AbstractDisplayer
{
    public function display($style = 'red')
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array)$this->value)->map(function ($name) use ($style) {

            return "<span class='badge bg-{$style}'>$name</span>";

        })->implode('&nbsp;');
    }
}
