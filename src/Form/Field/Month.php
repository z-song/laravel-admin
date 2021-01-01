<?php

namespace Encore\Admin\Form\Field;

class Month extends Date
{
    /**
     * @var array
     */
    protected $options = [
        'format'           => 'MM',
        'allowInputToggle' => true,
        'icons'            => [
            'time' => 'fas fa-clock',
        ],
    ];
}
