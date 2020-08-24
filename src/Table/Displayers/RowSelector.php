<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        return Admin::view('admin::table.display.row-selector', ['key' => $this->getKey()]);
    }
}
