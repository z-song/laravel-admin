<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Expand extends AbstractDisplayer
{
    private $uniqueKey;

    public function display($callback = null)
    {
        $callback = $callback->bindTo($this->row);

        $html = call_user_func_array($callback, [$this->row]);

        $key = $this->getKey();

        $this->uniqueKey = $key.'-'.$this->column->getName();

        $this->setupScript();

        return <<<EOT
<span class="grid-expand-{$this->uniqueKey}" data-inserted="0" data-key="{$key}" data-toggle="collapse" data-target="#grid-collapse-{$key}">
   <a href="javascript:void(0)"><i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;{$this->value}</a>
</span>
<template class="grid-expand-{$this->uniqueKey}">
    <div id="grid-collapse-{$key}" class="collapse">
        <div  style="padding: 10px 10px 0 10px;">$html</div>
    </div>
</template>
EOT;
    }

    protected function setupScript()
    {
        $script = <<<EOT

$('.grid-expand-{$this->uniqueKey}').on('click', function () {
    
    if ($(this).data('inserted') == '0') {
    
        var key = $(this).data('key');
        var row = $(this).closest('tr');
        var html = $('template.grid-expand-'+"$this->uniqueKey").html();

        row.after("<tr style='background-color: #ecf0f5;'><td colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'>"+html+"</td></tr>");

        $(this).data('inserted', 1);
    }
    
    $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
});
EOT;
        Admin::script($script);
    }
}
