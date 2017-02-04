<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid;

class ExportButton extends AbstractTool
{
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
        if (!$this->grid->allowExport()) {
            return '';
        }

        $export = trans('admin::lang.export');

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{$this->grid->exportUrl()}" target="_blank" class="btn btn-sm btn-twitter">
        <i class="fa fa-download"></i>&nbsp;&nbsp;{$export}
    </a>
</div>

EOT;
    }
}
