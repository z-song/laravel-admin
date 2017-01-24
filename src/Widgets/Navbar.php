<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Navbar implements Renderable
{
    protected $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function add($item)
    {
        $this->items->push($item);

        return $this;
    }

    public function render()
    {
        return $this->items->reverse()->map(function ($item) {

            if ($item instanceof Htmlable) {
                return $item->toHtml();
            }

            if ($item instanceof Renderable) {
                return $item->render();
            }

            return (string) $item;

        })->implode('');
    }
}
