<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Carousel extends Widget implements Renderable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var string
     */
    protected $title = 'Carousel';

    /**
     * Carousel constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * Set title.
     *
     * @param $title
     */
    public function title($title)
    {
        $this->title = $title;
    }

    /**
     * Render Carousel.
     *
     * @return string
     */
    public function render()
    {
        $variables = [
            'items' => $this->items,
            'title' => $this->title,
        ];

        return view('admin::widgets.carousel', $variables)->render();
    }
}
