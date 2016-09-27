<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Callout extends Widget implements Renderable
{
    protected $title = '';

    protected $content = '';

    protected $style = 'danger';

    public function __construct($content, $title = '', $style = 'danger')
    {
        $this->content = (string) $content;

        $this->title = $title;

        $this->style = $style;
    }

    public function style($style = 'info')
    {
        $this->style = $style;

        return $this;
    }

    protected function variables()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'style'   => $this->style,
        ];
    }

    public function render()
    {
        return view('admin::widgets.callout', $this->variables())->render();
    }
}
