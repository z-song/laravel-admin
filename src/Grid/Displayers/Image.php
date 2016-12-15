<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;

class Image extends AbstractDisplayer
{
    public function display($server = '', $width = 200, $height = 200)
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array)$this->value)->filter()->map(function ($path) use ($server, $width, $height) {

            if (url()->isValidUrl($path)) {
                $src = $path;
            } else {
                $server = $server ?: config('admin.upload.host');
                $src = trim($server, '/').'/'.trim($path, '/');
            }

            return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";
        })->implode('&nbsp;');
    }
}