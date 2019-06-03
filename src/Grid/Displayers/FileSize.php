<?php

namespace Encore\Admin\Grid\Displayers;

class FileSize extends AbstractDisplayer
{
    public function display()
    {
        return file_size($this->value);
    }
}
