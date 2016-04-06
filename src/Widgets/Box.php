<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Box extends Widget implements Renderable
{
    protected $attributes = [
        'class'     => [],
        'tools'     => [],
        'title'     => 'Box header',
        'content'   => 'here is the box content.'
    ];

    protected $tools = [];

    public function __construct($title = '', $content = '')
    {
        if ($title) {
            $this->title($title);
        }

        if ($content) {
            $this->content($content);
        }
    }

    public function content($content)
    {
        if ($content instanceof Renderable) {
            $this->attributes['content'] = $content->render();
        } else {
            $this->attributes['content'] = (string) $content;
        }

        return $this;
    }

    public function title($title)
    {
        $this->attributes['title'] = $title;
    }

    public function collapsable()
    {
        $this->attributes['tools'][] =
            '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';

        return $this;
    }

    public function removable()
    {
        $this->attributes['tools'][] =
            '<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>';

        return $this;
    }
    
    public function style($styles)
    {
        if (is_string($styles)) {
            return $this->style([$styles]);
        }

        $styles = array_map(function ($style) {
            return 'box-' . $style;
        }, $styles);

        $this->attributes['class'] = array_merge($this->attributes['class'], $styles);

        return $this;
    }
    
    public function solid()
    {
        $this->attributes['class'][] = 'box-solid';

        return $this;
    }
    
    public function render()
    {
        return view('admin::widgets.box', $this->attributes)->render();
    }
}
