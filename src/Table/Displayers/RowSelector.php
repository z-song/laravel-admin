<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        return Admin::view('admin::table.display.row-selector', [
            'row' => $this->table->getTableRowName(),
            'all' => $this->table->getSelectAllName(),
            'key' => $this->getKey(),
        ]);
    }
}
