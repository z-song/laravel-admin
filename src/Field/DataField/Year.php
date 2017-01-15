<?php

namespace Encore\Admin\Field\DataField;

class Year extends Date
{
    protected $format = 'YYYY';

    protected $view = 'admin::form.date';
}
