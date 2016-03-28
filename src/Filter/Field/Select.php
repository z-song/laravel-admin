<?php

namespace Encore\Admin\Filter\Field;

use Illuminate\Contracts\Support\Arrayable;

class Select
{
    protected $options = ['' => '请选择'];

    public function __construct($options)
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = $this->options + $options;
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
