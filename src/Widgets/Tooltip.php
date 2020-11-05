<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;

class Tooltip implements Renderable, Htmlable
{
    protected $placement;

    protected $message;

    public function __construct($message, $placement = 'bottom')
    {
        $this->message = $message;
        $this->placement = $placement;
    }

    public function render()
    {
        $vars = [
            'data-toggle'    => 'tooltip',
            'data-html'      => 'true',
            'data-placement' => $this->placement,
            'data-title'     => $this->message,
        ];

        return admin_view('admin::widgets.tooltip', compact('vars'));
    }

    public function toHtml()
    {
        return $this->render();
    }
}
