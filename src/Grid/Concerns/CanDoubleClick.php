<?php

namespace Encore\Admin\Grid\Concerns;

use Encore\Admin\Admin;

trait CanDoubleClick
{
    /**
     * Double-click grid row to jump to the edit page.
     *
     * @return $this
     */
    public function enableDblClick()
    {
        Admin::script(<<<SCRIPT
$('body').on('dblclick', 'table#{$this->tableID}>tbody>tr', function(e) {
    $.admin.redirect("{$this->resource()}/"+$(this).data('key')+"/edit");
});
SCRIPT);

        return $this;
    }
}
