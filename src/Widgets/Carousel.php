<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Carousel extends Widget implements Renderable
{
    protected $items;

    protected $title = 'Carousel';

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function title($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.carousel', ['items' => $this->items, 'title' => $this->title])->render();
    }
}
