<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Icon extends DataField
{
    protected static $css = [
        '/packages/admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

$('.{$this->getElementClass()}').iconpicker();

EOT;

        return parent::render();
    }
}
