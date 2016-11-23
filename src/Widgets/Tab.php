<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Tab extends Widget implements Renderable
{
    /**
     * @var array
     */
    protected $attributes = [
        'title'    => '',
        'tabs'     => [],
        'dropDown' => [],
    ];

    /**
     * Add a tab and its contents.
     *
     * @param string            $title
     * @param string|Renderable $content
     *
     * @return $this
     */
    public function add($title, $content)
    {
        $this->attributes['tabs'][] = [
            'title'   => $title,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function title($title = '')
    {
        $this->attributes['title'] = $title;
    }

    /**
     * Set drop-down items.
     *
     * @param array $links
     *
     * @return $this
     */
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
            'href' => $links[1],
        ];

        return $this;
    }

    /**
     * Render Tab.
     *
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.tab', $this->attributes)->render();
    }
}
