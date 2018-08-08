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
    if (this.checked) {
        $(this).closest('tr').css('background-color', '#ffffd5');
    } else {
        $(this).closest('tr').css('background-color', '');
    }
});

var {$this->grid->getSelectedRowsName()} = function () {
    var selected = [];
    $('.{$this->grid->getGridRowName()}-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    return selected;
}

EOT;
    }
}
