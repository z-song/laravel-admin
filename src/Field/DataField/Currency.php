<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Currency extends DataField
{
    protected $symbol = '$';

    /**
     * digits.
     *
     * @var int
     */
    protected $digits = 2;

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function prepare($value)
    {
        return (float) $value;
    }

    public function digits($number)
    {
        $this->digits = (int) $number;

        return $this;
    }

    public function render()
    {
        $this->script = <<<EOT

$('.{$this->getElementClass()}').inputmask("currency", {radixPoint: '.', prefix:'', digits:$this->digits, removeMaskOnSubmit: true})

EOT;

        return parent::render()->with(['symbol' => $this->symbol]);
    }
}
