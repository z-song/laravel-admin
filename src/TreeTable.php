<?php

namespace Encore\Admin;

use Encore\Admin\Table\Column;

class TreeTable extends Table
{
    public function render()
    {
        $treeColumn = $this->model->getOriginalModel()->getTitleColumn();

        /** @var Column $column */
        foreach ($this->columns as $column) {
            if ($column->getName() == $treeColumn) {
                $column->buildTree();
                break;
            }
        }

        return parent::render();
    }
}
