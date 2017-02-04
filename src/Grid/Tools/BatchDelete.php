<?php

namespace Encore\Admin\Grid\Tools;

class BatchDelete extends BatchAction
{
    /**
     * Script of batch delete action.
     */
    public function script()
    {
        $confirm = trans('admin::lang.delete_confirm');
        $message = trans('admin::lang.delete_succeeded');

        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    if(confirm("{$confirm}")) {
        $.ajax({
            method: 'post',
            url: '{$this->resource}/' + selectedRows().join(),
            data: {
                _method:'delete',
                _token:'{$this->getToken()}'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');
                toastr.success('{$message}');
            }
        });
    }
});

EOT;
    }
}
