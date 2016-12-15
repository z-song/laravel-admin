<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;

class Label extends AbstractDisplayer
{
    public function display($style = 'success')
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array)$this->value)->map(function ($name) use ($style) {

            return "<span class='label label-{$style}'>$name</span>";

        })->implode('&nbsp;');
    }
}
