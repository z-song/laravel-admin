<?php

namespace Encore\Admin\Form\Field;

class Month extends Date
{
    protected $format = 'MM';

    public function getView()
    {
        return 'admin::form.date';
    }
}
