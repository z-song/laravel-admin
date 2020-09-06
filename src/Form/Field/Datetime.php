<?php

namespace Encore\Admin\Form\Field;

class Datetime extends Date
{
    protected $icon = 'fa-calendar';

    /**
     * @var array
     */
    protected $options = [
        'format'           => 'YYYY-MM-DD HH:mm:ss',
        'allowInputToggle' => true,
        'icons'            => [
            'time' => 'fas fa-clock',
        ],
    ];
}
