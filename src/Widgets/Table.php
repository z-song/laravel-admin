<?php

namespace Encore\Admin\Widgets;

class Table extends Widget
{
    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.table')->render();
    }
}
