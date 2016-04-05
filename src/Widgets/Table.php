<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Table extends Widget implements Renderable
{
    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.table')->render();
    }
}
