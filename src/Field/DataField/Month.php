<?php

namespace Encore\Admin\Field\DataField;

class Month extends Date
{
    protected $format = 'MM';

    protected $view = 'admin::field.date';
}
