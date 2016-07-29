<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Collapse extends Widget implements Renderable
{
    protected $items = [];

    public function add($title, $content)
    {
        $this->items[] = [
            'title'   => $title,
            'content' => $content,
        ];

        return $this;
    }

    public function render()
    {
        return view('admin::widgets.collapse', ['items' => $this->items])->render();
    }
}
