<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Facades\Admin;

/**
 * Class QRCode.
 */
class QRCode extends AbstractDisplayer
{
    protected function addScript($logo='')
    {
        $script = <<<'SCRIPT'
$('.grid-column-qrcode').popover({
    html: true,
    trigger: 'focus'
});
SCRIPT;

        Admin::script($script);

        if(!empty($logo)) {
            $css = <<<STYLE
.qr-code-wrapper {
    position:relative;
    margin:0;
    padding:0;
}
.qr-code-wrapper:after{
    content:"";
    position:absolute;
    left:50%;
    top:50%;
    width:24%;
    height:24%;
    background:url($logo) no-repeat;
    background-size:100% auto;
    margin:-12% 0 0 -12%;
    border-radius:10px;
    border:3px solid #fff;
}
STYLE;
        } else {
            $css = <<<STYLE
.qr-code-wrapper {
    position:relative;
    margin:0;
    padding:0;
}
STYLE;
        }
        Admin::style($css);
    }

    public function display($formatter = null, $width = 150, $height = 150, $logo='')
    {
        $this->addScript($logo);

        $content = $this->getColumn()->getOriginal();

        if ($formatter instanceof \Closure) {
            $content = call_user_func($formatter, $content, $this->row);
        }

        $img = sprintf(
            "<p class='qr-code-wrapper'><img src='https://api.qrserver.com/v1/create-qr-code/?size=%sx%s&data=%s' style='height:%spx;width:%spx;'/></p>",
            $width, $height, $content, $height, $width
        );

        return <<<HTML
<a href="javascript:void(0);" class="grid-column-qrcode text-muted" data-content="{$img}" data-toggle='popover' tabindex='0'>
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{$this->getValue()}
HTML;
    }
}
