<?php

namespace Encore\Admin\Form\Field;

class Mobile extends Text
{
    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'mask' => '99999999999',
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prependText('<i class="fa fa-phone fa-fw"></i>')
            ->defaultAttribute('style', 'width: 150px');

        return parent::render();
    }
}
