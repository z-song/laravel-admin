<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Widgets\InfoBox;

class Dashboard
{
    protected $infoBox;

    public function __construct(Closure $callback)
    {
        $callback($this);
    }

    public function infoBox()
    {
        return $this->infoBox = new InfoBox();
    }

    protected $left = [];

    public function left(Closure $callback)
    {
        $this->left = collect();

        $callback($this->left);
    }

    protected $right = [];

    public function right(Closure $callback)
    {
        $this->right = collect();

        $callback($this->right);
    }

    protected $bottom = [];

    public function bottom(Closure $callback)
    {
        $this->bottom = collect();

        $callback($this->bottom);
    }

    protected function variables()
    {
        $variables = ['title' => 'Dashboard', 'description' => 'Control panel'];

        $partials = [
            'infoBox'   => $this->infoBox,
            'left'      => $this->left,
            'right'     => $this->right,
            'bottom'    => $this->bottom,
        ];

        return array_merge($variables, $partials);
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
