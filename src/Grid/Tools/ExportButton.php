<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;

class ExportButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showExportBtn()) {
            return '';
        }

        return Admin::view('admin::grid.export-btn', [
            'name'     => $this->grid->getExportSelectedName(),
            'all'      => $this->grid->getExportUrl('all'),
            'page'     => $this->grid->getExportUrl('page', request('page', 1)),
            'selected' => $this->grid->getExportUrl('selected', '__rows__'),
        ]);
    }
}
