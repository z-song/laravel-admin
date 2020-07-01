<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        return Admin::view('admin::grid.display.row-selector', [
            'row' => $this->grid->getGridRowName(),
            'all' => $this->grid->getSelectAllName(),
            'key' => $this->getKey(),
        ]);
    }
}
