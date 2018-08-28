<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;

class RefreshButton extends AbstractTool
{
    /**
     * Script for this tool.
     *
     * @return string
     */
    protected function script()
    {
        $message = trans('admin.refresh_succeeded');

        return <<<EOT

$('.grid-refresh').on('click', function() {
    $.pjax.reload('#pjax-container');
    toastr.success('{$message}');
});

EOT;
    }

    /**
     * Render refresh button of grid.
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $refresh = trans('admin.refresh');

        return <<<EOT
<a class="btn btn-sm btn-primary grid-refresh" title="$refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> $refresh</span></a>
EOT;
    }
}
