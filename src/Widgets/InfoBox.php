<?php

namespace Encore\Admin\Widgets;

class InfoBox
{
    protected $attributes = [];

    /**
     * InfoBox constructor.
     *
     * @param string $name
     * @param string $icon
     * @param string $color
     * @param string $link
     * @param string $info
     *
     * @return InfoBox
     */
    public function add($name, $icon, $color, $link, $info)
    {
        $this->attributes[] = [
            'name'  => $name,
            'icon'  => $icon,
            'color' => $color,
            'link'  => $link,
            'info'  => $info
        ];

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.box')->with(['boxes' => $this->attributes])->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
