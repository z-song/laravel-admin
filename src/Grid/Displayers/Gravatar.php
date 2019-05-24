<?php

namespace Encore\Admin\Grid\Displayers;

class Gravatar extends AbstractDisplayer
{
    public function display($size = 30)
    {
        $src = sprintf('https://www.gravatar.com/avatar/%s?s=%d', md5(strtolower($this->value)), $size);

        return "<img src='$src' class='img img-circle'/>";
    }
}
