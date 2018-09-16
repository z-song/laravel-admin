<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Admin;

class Image extends AbstractDisplayer
{
    public function display($server = '', $width = 200, $height = 200, $expand = false)
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array) $this->value)->filter()->map(function ($path) use ($server, $width, $height, $expand) {
            if (url()->isValidUrl($path)) {
                $src = $path;
            } elseif ($server) {
                $src = $server.$path;
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }

            if ($expand) Admin::script("$('img.img-expand').on('click', function() { $('.img-expanded').attr('src', $(this).attr('src')); $('#img-expand-" . $this->getKey() . "').modal('show'); });");
            return ($expand?'<div class="modal fade" id="img-expand-' . $this->getKey() . '" tabindex="-1" role="dialog" aria-labelledby="img-expand" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-body"><img src="" class="img-expanded" style="width: 100%;"></div></div></div></div>':'') . "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail" . ($expand?' img-expand':'') . "' />";
        })->implode('&nbsp;');
    }
}
