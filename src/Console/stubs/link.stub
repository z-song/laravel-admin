<?php

namespace App\Admin\Extensions\Nav;

use Illuminate\Contracts\Support\Renderable;

class Link implements Renderable
{
    protected $title;

    protected $href;

    protected $icon;

    public function __construct($title, $href, $icon = 'fa-gears')
    {
        $this->title = $title;
        $this->href = $href;
        $this->icon = $icon;
    }

    public static function make($title, $href, $icon = 'fa-gears')
    {
        return new static($title, $href, $icon);
    }

    public function render()
    {
        $link = admin_url($this->href);

        $icon = '';

        if ($this->icon) {
            $icon = "<i class=\"fa {$this->icon}\"></i>";
        }

        return <<<HTML
<li>
    <a href="{$link}">
      {$icon}
      <span>{$this->title}</span>
    </a>
</li>

HTML;
    }
}