<?php

namespace Encore\Admin\Table\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Badge extends AbstractDisplayer
{
    public function display($style = '')
    {
        if (empty($style)) {
            $style = admin_color();
        }

        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->map(function ($name) use ($style) {
            if (is_array($style)) {
                $style = Arr::get($style, $this->getOriginalValue(), 'red');
            }

            return "<span class='badge badge-pill badge-{$style}'>$name</span>";
        })->implode('&nbsp;');
    }
}
