<?php

namespace Encore\Admin\Form\Field;

class Time extends Date
{
    protected $icon = 'fa-clock';

    /**
     * @var array
     */
    protected $options = [
        'format'           => 'HH:mm:ss',
        'allowInputToggle' => true,
        'icons'            => [
            'time' => 'fas fa-clock',
        ],
    ];
}
