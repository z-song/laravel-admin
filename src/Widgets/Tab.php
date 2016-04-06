<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Tab extends Widget implements Renderable
{
    protected $attributes = [
        'title' => ''
    ];

    public function add($title, $content)
    {
        $this->attributes['tabs'][] = [
            'title' => $title,
            'content' => $content,
        ];

        return $this;
    }

    public function title($title = '')
    {
        $this->attributes['title'] = $title;
    }

    public function dropDown(array $links)
    {
        if (is_array($links[0])) {
            foreach ($links as $link) {
                call_user_func([$this, 'dropDown'], $link);
            }

            return $this;
        }

        $this->attributes['dropDown'][] = [
            'name' => $links[0],
            'href' => $links[1]
        ];

        return $this;
    }
    
    public function render()
    {
        return view('admin::widgets.tab', $this->attributes)->render();
    }
}

