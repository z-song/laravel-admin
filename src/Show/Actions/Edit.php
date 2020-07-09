<?php

namespace Encore\Admin\Show\Actions;

use Illuminate\Contracts\Support\Renderable;

class Edit implements Renderable
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function render()
    {
        $text = trans('admin.edit');

        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$this->path}" class="btn btn-sm btn-primary" title="{$text}">
        <i class="fa fa-edit"></i><span class="hidden-xs"> {$text}</span>
    </a>
</div>
HTML;
    }
}
