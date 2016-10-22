<?php

namespace Encore\Admin\Form\Field;

class Year extends Date
{
    protected $format = 'YYYY';

    public function getView()
    {
        return 'admin::form.date';
    }
}
