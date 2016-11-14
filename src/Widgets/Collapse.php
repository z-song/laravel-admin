<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Collapse extends Widget implements Renderable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Add item.
     *
     * @param string $title
     * @param string $content
     *
     * @return $this
     */
    public function add($title, $content)
    {
        $this->items[] = [
            'title'   => $title,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * Render Collapse.
     *
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.collapse', ['items' => $this->items])->render();
    }
}
