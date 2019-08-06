<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Facades\Admin;

/**
 * Class Copyable.
 *
 * @see https://codepen.io/shaikmaqsood/pen/XmydxJ
 */
class Copyable extends AbstractDisplayer
{
    protected function addScript()
    {
        $script = <<<SCRIPT
$('#{$this->grid->tableID}').on('click','.grid-column-copyable', function (e) {
    var copyLink = $(this);
    var content = copyLink.data('content');
    
    var temp = $('<input>');
    
    $("body").append(temp);
    temp.val(content).select();
    document.execCommand("copy");
    temp.remove();
    
    copyLink.focusout()
        .attr('title', copyLink.data('copied-title'))
        .tooltip('fixTitle')
        .tooltip('show')
        .on('mouseleave focusout', function() {
            copyLink.attr('title', copyLink.data('copy-title'))
                .tooltip('fixTitle');
        });      
});

$('#{$this->grid->tableID} .grid-column-copyable')
    .tooltip({
        trigger: 'manual'
    })
    .mouseenter(function() {
        $(this).tooltip('show');
    })
    .mouseleave(function(e) {
        $(this).tooltip('hide');
    });
SCRIPT;

        Admin::script($script);
    }

    public function display()
    {
        $this->addScript();

        $content = $this->getColumn()->getOriginal();

        $copy   = __('admin.copy');
        $copied = __('admin.copied');

        return <<<HTML
<a href="javascript:void(0);" class="grid-column-copyable text-muted" data-content="{$content}" data-placement="bottom" title="{$copy}" data-copy-title="{$copy}" data-copied-title="{$copied}">
    <i class="fa fa-copy"></i>
</a>&nbsp;{$this->getValue()}
HTML;
    }
}
