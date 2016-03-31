<?php

namespace Encore\Admin\Widgets;

class Table
{
    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.table')->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
