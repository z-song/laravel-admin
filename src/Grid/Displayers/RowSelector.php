<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        Admin::script($this->script());

        return <<<EOT
<input type="checkbox" class="{$this->grid->getGridRowName()}-checkbox" data-id="{$this->getKey()}" />
EOT;
    }

    protected function script()
    {
        return <<<EOT
$('.{$this->grid->getGridRowName()}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {
    
    var id = $(this).data('id');

    if (this.checked) {
        \$.admin.grid.select(id);
        $(this).closest('tr').css('background-color', '#ffffd5');
    } else {
        \$.admin.grid.unselect(id);
        $(this).closest('tr').css('background-color', '');
    }
});

EOT;
    }
}
