<?php

namespace Encore\Admin\Show\Actions;

use Illuminate\Contracts\Support\Renderable;

class _List implements Renderable
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function render()
    {
        $text = trans('admin.list');

        return <<<HTML
<div class="btn-group float-right" style="margin-right: 5px">
    <a href="{$this->path}" class="btn btn-sm btn-default" title="$text">
        <i class="fa fa-list"></i><span class="hidden-xs">&nbsp;$text</span>
    </a>
</div>
HTML;
    }
}
