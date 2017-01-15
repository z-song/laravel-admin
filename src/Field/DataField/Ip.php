<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Ip extends DataField
{
    protected $rules = 'ip';

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}
