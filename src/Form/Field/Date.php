<?php

namespace Encore\Admin\Form\Field;

class Date extends Text
{
    protected $format = 'YYYY-MM-DD';

    protected $view = 'admin::form.date';

    protected $icon = 'fa-calendar-alt';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->options = array_merge([
            'format'           => $this->format,
            'locale'           => $this->options['locale'] ?? config('app.locale'),
            'allowInputToggle' => true,
            'icons'            => [
                'time' => 'fas fa-clock',
            ],
        ], $this->options);

        $this->addVariables([
            'icon'    => $this->icon,
            'options' => $this->options,
        ]);

        $this->attribute(['autocomplete' => 'off']);

        return parent::render();
    }
}
