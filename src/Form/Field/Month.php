<?php

namespace Encore\Admin\Form\Field;

class Month extends Date
{
    protected $format = 'MM';

    protected $view = 'admin::form.date';
}
