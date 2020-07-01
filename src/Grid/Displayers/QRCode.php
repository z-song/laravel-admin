<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

/**
 * Class QRCode.
 */
class QRCode extends AbstractDisplayer
{
    public function display($formatter = null, $width = 150, $height = 150)
    {
        $content = $this->getOriginalValue();
        $value   = $this->getValue();

        if ($formatter instanceof \Closure) {
            $content = call_user_func($formatter, $content, $this->row);
        }

        $img = sprintf(
            "<img src='https://api.qrserver.com/v1/create-qr-code/?size=%sx%s&data=%s' style='height:%spx;width:%spx;'/>",
            $width,
            $height,
            $content,
            $height,
            $width
        );

        return Admin::view('admin::grid.display.qrcode', compact('img', 'value'));
    }
}
