<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Table;

class CreateButton extends AbstractTool
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * Create a new CreateButton instance.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->table->showCreateBtn()) {
            return '';
        }

        $new = trans('admin.new');

        return <<<SCRIPT
<div class="btn-group float-right table-create-btn mr-2">
    <a href="{$this->table->getCreateUrl()}" class="btn btn-sm btn-success" title="{$new}">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>
SCRIPT;
    }
}
