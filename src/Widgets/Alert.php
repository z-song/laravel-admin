<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Alert extends Widget implements Renderable
{
    protected $title = '';

    protected $content = '';

    protected $style = 'danger';

    protected $icon = 'ban';

    public function __construct($content, $title = '', $style = 'danger')
    {
        $this->content = (string) $content;

        $this->title = $title ?: trans('admin::lang.alert');

        $this->style = $style;
    }

    public function style($style = 'info')
    {
        $this->style = $style;

        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    protected function variables()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'style'   => $this->style,
            'icon'    => $this->icon,
        ];
    }

    public function render()
    {
        return view('admin::widgets.alert', $this->variables())->render();
    }
}
