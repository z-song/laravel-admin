<?php

namespace Encore\Admin\Grid\Displayers;

class Image extends AbstractDisplayer
{
    public function display($server = '', $width = 200, $height = 200)
    {
        if (!$this->value) {
            return '';
        }

        if (url()->isValidUrl($this->value)) {
            $src = $this->value;
        } else {
            $server = $server ?: config('admin.upload.host');
            $src = trim($server, '/').'/'.trim($this->value, '/');
        }

        return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";
    }
}