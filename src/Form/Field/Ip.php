<?php

namespace Encore\Admin\Form\Field;

class Ip extends Text
{
    protected $rules = 'ip';

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias' => 'ip'
    ];

    public function render()
    {
        $options = json_encode($this->options);

        $this->script = <<<EOT

$('.{$this->getElementClass()}').inputmask($options);
EOT;

        $this->prepend('<i class="fa fa-laptop"></i>')
            ->defaultAttribute('style', 'width: 130px');

        return parent::render();
    }
}
