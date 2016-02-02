<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

class Select extends Field
{
    protected $css = [
        'AdminLTE/plugins/select2/select2.min.css'
    ];

    protected $js = [
        'AdminLTE/plugins/select2/select2.full.min.js'
    ];

    public function render()
    {
        $this->script = "$(\"#{$this->id}\").select2({allowClear: true});";

        return parent::render()->with(['options' => $this->options]);
    }

    public function options($options = [])
    {
        if($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = $options;

        return $this;
    }
}