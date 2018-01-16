<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;

class Label extends AbstractDisplayer
{
    //fixed by kingstudio at 2018-1-16
    //fix label too long bug, add newline parameter, if label number more then newline, add <br/>
    //
    public function display($style = 'success', $newline = 5)
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->map(function ($name, $key) use ($style, $newline) {
            if (($key + 1) % $newline == 0)
                return "<span class='label label-{$style}'>$name</span><br/>";
            else
                return "<span class='label label-{$style}'>$name</span>&nbsp;";
        })->implode('');
    }
}
