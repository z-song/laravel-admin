<?php

namespace Encore\Admin\Table\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Label extends AbstractDisplayer
{
    public function display($style = '')
    {
        if (empty($style)) {
            $style = admin_color();
        }

        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->map(function ($item) use ($style) {
            if (is_array($style)) {
                if (is_string($this->getOriginalValue()) || is_int($this->getOriginalValue())) {
                    $style = Arr::get($style, $this->getOriginalValue(), 'success');
                } else {
                    $style = Arr::get($style, $item, 'success');
                }
            }

            return "<span class='badge badge-{$style}'>$item</span>";
        })->implode('&nbsp;');
    }
}
