<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Table;

class ExportButton extends AbstractTool
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * Create a new Export button instance.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->table->showExportBtn()) {
            return '';
        }

        return Admin::view('admin::table.export-btn', [
            'name'     => $this->table->getExportSelectedName(),
            'all'      => $this->table->getExportUrl('all'),
            'page'     => $this->table->getExportUrl('page', request('page', 1)),
            'selected' => $this->table->getExportUrl('selected', '__rows__'),
        ]);
    }
}
