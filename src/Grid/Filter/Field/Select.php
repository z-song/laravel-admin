<?php

namespace Encore\Admin\Grid\Filter\Field;

use Illuminate\Contracts\Support\Arrayable;

class Select
{
    protected $options = [];

    public function __construct($options)
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = ['' => trans('admin::lang.choose')] + $options;
    }

    public function variables()
    {
        return ['options' => $this->options];
    }

    public function name()
    {
        return 'select';
    }
}
