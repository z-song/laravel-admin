<?php

namespace Encore\Admin\Form\Field;

class DatetimeRange extends DateRange
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
