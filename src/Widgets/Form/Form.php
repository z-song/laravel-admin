<?php

namespace Encore\Admin\Widgets\Form;

use Illuminate\Contracts\Support\Renderable;

class Form implements Renderable
{
    protected $filters = [];

    public function addFilter($filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.form');
    }

    public function __toString()
    {
        return $this->render();
    }
}
