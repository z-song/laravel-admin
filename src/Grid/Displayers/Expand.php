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

        $rowKey = $this->getKey();
        $key = uniqid();
<<<<<<< HEAD
<<<<<<< HEAD
        $val = $this->value ?: $def;

        return <<<EOT
<a href="javascript:;" class="btn btn-xs btn-default grid-expand collapsed" data-key="{$key}" data-row-key="{$rowKey}" data-parent="grid-collapse-group-{$rowKey}" data-toggle="collapse" data-target="#grid-collapse-{$key}">
   &nbsp;&nbsp;{$val}
</a>
<template class="grid-collapse-tpl-{$key}">
    <div id="grid-collapse-{$key}" class="collapse grid-collapse-group-{$rowKey}">
=======
=======
        $val = $this->value ?: $def;
>>>>>>> 44bd2756... 增加一个参数，避免一对多建立空数据时，按钮无数据。

        return <<<EOT
<style>
  .grid-expand:before{content:"\\f103";display: inline-block;font: normal normal normal 14px/1 FontAwesome;font-size: inherit;text-rendering: auto;-webkit-font-smoothing: antialiased;}
  .grid-expand.collapsed:before{content:"\\f102";}
</style>
<a href="javascript:void(0)" class="grid-expand collapsed" data-inserted="0" data-key="{$key}" data-row-key="{$rowKey}" data-parent="grid-expand-{$rowKey}" data-toggle="collapse" data-target="#grid-collapse-{$key}">
   &nbsp;&nbsp;{$val}
</a>
<template class="grid-expand-{$key}">
    <div id="grid-collapse-{$key}" class="collapse grid-expand-{$rowKey}">
>>>>>>> 7bd1915d... fix #1179
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
    
<<<<<<< HEAD
    $('.grid-expand:not(.collapsed)').each(function(){
        $(this).attr("id") == '#grid-collapse-' + key || $(this).addClass('collapsed');
    });

=======
>>>>>>> 7bd1915d... fix #1179
    if( ! $('#grid-collapse-' + key).length ) {
        var row = $(this).closest('tr');
        var html = $('template.grid-collapse-tpl-'+key).html();

<<<<<<< HEAD
        $('#grid-collapse-group-' + rowkey).length || row.after("<tr style='background-color: #ecf0f5;'><td id='grid-collapse-group-" + rowkey + "' colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'></td></tr>");
        
        $('#grid-collapse-group-' + rowkey).html(html);
=======
        $('#grid-expand-' + rowkey).length || row.after("<tr style='background-color: #ecf0f5;'><td id='grid-expand-" + rowkey + "' colspan='"+(row.find('td').length)+"' style='padding:0 !important; border:0;'></td></tr>");
        
        $('#grid-expand-' + rowkey).html(html);
>>>>>>> 7bd1915d... fix #1179

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
