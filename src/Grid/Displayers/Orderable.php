<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Orderable extends AbstractDisplayer
{
    public function display()
    {
        if (!trait_exists('\Spatie\EloquentSortable\SortableTrait')) {
            throw new \Exception('To use orderable grid, please install package [spatie/eloquent-sortable] first.');
        }

        return Admin::view('admin::grid.display.orderable', [
            'key'      => $this->getKey(),
            'resource' => $this->getResource(),
        ]);
    }
}
