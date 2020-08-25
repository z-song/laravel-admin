<?php

namespace Encore\Admin\Table\Displayers;

class ProgressBar extends AbstractDisplayer
{
    public function display($style = '', $size = 'sm', $max = 100)
    {
        $style = $style ?: admin_color();

        $style = collect((array) $style)->map(function ($style) {
            return 'progress-bar-'.$style;
        })->implode(' ');

        $this->value = (int) $this->value;

        return <<<HTML
<div class="row" style="min-width: 100px;">
    <span class="col-sm-3" style="color:#777;">{$this->value}%</span>
    <div class="progress progress-$size col-sm-9" style="padding-left: 0;width: 100px;">
        <div class="progress-bar $style" role="progressbar" aria-valuenow="{$this->value}" aria-valuemin="0" aria-valuemax="$max" style="width: {$this->value}%">
        </div>
    </div>
</div>
HTML;
    }
}
