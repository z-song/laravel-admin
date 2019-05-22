<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Expand extends AbstractDisplayer
{
    public function display($callback = null, $def = '')
    {
        $callback = $callback->bindTo($this->row);

        $html = call_user_func_array($callback, [$this->row]);

        $this->setupStyle();
        $this->setupScript();

        $key = $this->column->getName().'-'.$this->getKey();
        $rowKey = $this->getKey();
        $val = $this->value ?: $def;

        return <<<EOT
<a href="javascript:;" class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{$key}" data-row-key="{$rowKey}" data-parent="grid-collapse-group-{$rowKey}" data-toggle="collapse" data-target="#grid-collapse-{$key}">
   &nbsp;&nbsp;{$val}
</a>
<template class="grid-collapse-tpl-{$key}">
    <div id="grid-collapse-{$key}" class="collapse grid-collapse-group-{$rowKey}">
        <div  style="padding: 10px 10px 0 10px;">$html</div>
    </div>
</template>
EOT;
    }

    protected function setupScript()
    {
        $script = <<<'EOT'
$('.grid-expand').on('click', function () {
    
    var key = $(this).data('key');
    var rowkey = $(this).data('rowKey');
    
    $('.grid-expand:not(.collapsed)').each(function(){
        $(this).attr("id") == '#grid-collapse-' + key || $(this).addClass('collapsed');
    });
    
    if( ! $('#grid-collapse-' + key).length ) {
        var row = $(this).closest('tr');
        var html = $('template.grid-collapse-tpl-'+key).html();
        $('#grid-collapse-group-' + rowkey).length || row.after("<tr style='background-color: #ecf0f5;'><td id='grid-collapse-group-" + rowkey + "' colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'></td></tr>");
        $('#grid-collapse-group-' + rowkey).html(html);
    }
});
EOT;
        Admin::script($script);
    }

    protected function setupStyle()
    {
        $style = <<<STYLE
        .grid-expand:before{content:"\\f0d7";display: inline-block;font: normal normal normal 14px/1 FontAwesome;font-size: inherit;text-rendering: auto;-webkit-font-smoothing: antialiased;}
        .grid-expand.collapsed:before{content:"\\f0da";}
STYLE;
        Admin::style( $style );
    }
}
