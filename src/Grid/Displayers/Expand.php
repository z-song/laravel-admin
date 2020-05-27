<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class Expand extends AbstractDisplayer
{
    protected $renderable;

    public function display($callback = null, $isExpand = false)
    {
        if (is_subclass_of($callback, Renderable::class)) {
            $html = <<<'HTML'
<div class="loading text-center" style="padding: 20px 0px;">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
</div>
HTML;
            $this->renderable = $callback;
            $this->addRenderableModalScript();
        } else {
            $callback = $callback->bindTo($this->row);

            $html = call_user_func_array($callback, [$this->row]);

            $this->addScript($isExpand);
        }

        $key = $this->column->getName().'-'.$this->getKey();

        return <<<EOT
<span class="{$this->getElementClass()}" data-inserted="0" data-pk="{$this->getKey()}" data-key="{$key}" data-toggle="collapse" data-target="#grid-collapse-{$key}">
   <a href="javascript:void(0)"><i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;{$this->value}</a>
</span>
<template class="grid-expand-{$key}">
    <div id="grid-collapse-{$key}" class="collapse">
        <div style="padding: 10px 10px 0 10px;" class="html">$html</div>
    </div>
</template>
EOT;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $renderable = str_replace('\\', '_', $this->renderable);

        return route('admin.handle-renderable', compact('renderable'));
    }

    protected function addRenderableModalScript()
    {
        $script = <<<SCRIPT
$('.{$this->getElementClass()}').on('click', function () {
    var target = $(this);
    if (target.data('inserted') == '0') {
        var pk  = target.data('pk');
        var key = $(this).data('key');
        var row = $(this).closest('tr');
        var html = $('template.grid-expand-'+key).html();

        row.after("<tr style='background-color: #ecf0f5;'><td colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'>"+html+"</td></tr>");

        $(this).data('inserted', 1);

        $.get('{$this->getLoadUrl()}'+'&key='+pk, function (data) {
            $('#grid-collapse-'+key).find('.html').html(data);
        });
    }

    $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
});
SCRIPT;

        Admin::script($script);
    }

    protected function addScript($isExpand)
    {
        $script = <<<SCRIPT
$('.{$this->getElementClass()}').on('click', function () {

    if ($(this).data('inserted') == '0') {

        var key = $(this).data('key');
        var row = $(this).closest('tr');
        var html = $('template.grid-expand-'+key).html();

        row.after("<tr style='background-color: #ecf0f5;'><td colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'>"+html+"</td></tr>");

        $(this).data('inserted', 1);
    }

    $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
});
SCRIPT;

        if ($isExpand) {
            $script .= "$('.{$this->getElementClass()}').trigger('click');";
        }

        Admin::script($script);
    }

    /**
     * @return string
     */
    protected function getElementClass()
    {
        return "grid-expand-{$this->grid->getGridRowName()}";
    }
}
