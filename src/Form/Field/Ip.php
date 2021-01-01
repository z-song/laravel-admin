<?php

namespace Encore\Admin\Form\Field;

class Ip extends Text
{
    protected $rules = 'nullable|ip';

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias' => 'ip',
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prependText('<i class="fa fa-laptop fa-fw"></i>')
            ->defaultAttribute('style', 'width: 130px');

        return parent::render();
    }
}
