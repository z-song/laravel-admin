<?php

namespace Encore\Admin\Form\Field;

class Year extends Date
{
    /**
     * @var array
     */
    protected $options = [
        'format'           => 'YYYY',
        'allowInputToggle' => true,
        'icons'            => [
            'time' => 'fas fa-clock',
        ],
    ];
}
