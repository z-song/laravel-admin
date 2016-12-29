<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;

class BatchActions extends AbstractTool
{
    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->allowBatchDeletion()) {
            return '';
        }

        Admin::script($this->script());

        $delete = trans('admin::lang.delete');
        $action = trans('admin::lang.action');

        return <<<EOT

<input type="checkbox" class="grid-select-all" />&nbsp;

<div class="btn-group">
    <a class="btn btn-sm btn-default">  $action</a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="#" class="grid-batch-delete">$delete</a></li>
    </ul>
</div>

EOT;
    }

    /**
     * Scripts of BatchActions button groups.
     *
     * @return string
     */
    protected function script()
    {
        $token = csrf_token();
        $confirm = trans('admin::lang.delete_confirm');
        $message = trans('admin::lang.delete_succeeded');
        $path = $this->grid->resource();

        return <<<EOT

$('.grid-select-all').iCheck({checkboxClass:'icheckbox_minimal-blue'});

$('.grid-select-all').on('ifChanged', function(event) {
    if (this.checked) {
        $('.grid-row-checkbox').iCheck('check');
    } else {
        $('.grid-row-checkbox').iCheck('uncheck');
    }
});

$('.grid-batch-delete').on('click', function() {
    var selected = [];
    $('.grid-row-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    if (selected.length == 0) {
        return;
    }

    if(confirm("{$confirm}")) {
        $.post('/{$path}/' + selected.join(), {_method:'delete','_token':'{$token}'}, function(data){
            $.pjax.reload('#pjax-container');
            toastr.success('{$message}');
        });
    }
});

EOT;

    }
}
