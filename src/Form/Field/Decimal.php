<?php

namespace Encore\Admin\Form\Field;

class Decimal extends Text
{
    /**
     * @var string
     */
    protected $icon = 'fa-terminal';

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias'      => 'decimal',
        'rightAlign' => true,
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prependText('<i class="fa '.$this->icon.' fa-fw"></i>')
            ->defaultAttribute('style', 'width: 130px');

        return parent::render();
    }
}
