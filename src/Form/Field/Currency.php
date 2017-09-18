<?php

namespace Encore\Admin\Form\Field;

class Currency extends Text
{
    protected $symbol = '$';

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias'                 => 'currency',
        'radixPoint'            => '.',
        'prefix'                => '',
        'removeMaskOnSubmit'    => true,
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

    public function render()
    {
        $options = json_encode($this->options);

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').inputmask($options);

EOT;

        $this->prepend($this->symbol)
            ->defaultAttribute('style', 'width: 120px');

        return parent::render();
    }
}
