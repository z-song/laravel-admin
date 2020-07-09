<?php

namespace Encore\Admin\Form\Actions;

use Illuminate\Contracts\Support\Renderable;

class View implements Renderable
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function render()
    {
        $view = trans('admin.view');

        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$this->path}" class="btn btn-sm btn-primary" title="{$view}">
        <i class="fa fa-eye"></i><span class="hidden-xs"> {$view}</span>
    </a>
</div>
HTML;
    }
}
