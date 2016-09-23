<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Date extends AbstractField
{
    protected $format = 'YYYY-MM-DD';

    protected $options = [];

    public function render()
    {
        $this->options['format'] = $this->format;
        $this->options['locale'] = config('app.locale');

        $this->script = "$('#{$this->id()}').datetimepicker(".json_encode($this->options).');';

        return parent::render();
    }
}
