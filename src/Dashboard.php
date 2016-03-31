<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Table;

class Dashboard
{
    protected $widgets = [];

    public function __construct(Closure $callback)
    {
        $callback($this);
    }

    public function infoBox()
    {
        return $this->widgets['infoBox'] = new InfoBox();
    }

    public function table()
    {
        return $this->widgets['table'] = new Table();
    }

    protected function variables()
    {
        $variables = ['title' => 'Dashboard', 'description' => 'Control panel'];

        return array_merge($variables, $this->widgets);
    }

    public function render()
    {
        return view('admin::dashboard', $this->variables())->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
