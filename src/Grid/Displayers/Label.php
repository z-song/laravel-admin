<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Label extends AbstractDisplayer
{
    public function display($style = 'success')
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->map(function ($item) use ($style) {

            if (is_array($style)) {
                $style = Arr::get($style, $this->getColumn()->getOriginal(), 'success');
            }

            return "<span class='label label-{$style}'>$item</span>";
        })->implode('&nbsp;');
    }
}
