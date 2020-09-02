<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class DateRange extends Field
{
    protected $format = 'YYYY-MM-DD';

    protected $view = 'admin::form.daterange';

    protected $icon = 'fa-calendar-alt';

    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    public function __construct($column, $arguments)
    {
        $this->column['start'] = $column;
        $this->column['end'] = $arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            if (!is_null($this->value) && is_null($this->value['start']) && is_null($this->value['end'])) {
                return $this->getDefault();
            }

            return $this->value;
        }

        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->options = array_merge($this->options, [
            'locale' => config('app.locale'),
            'format' => $this->format,
            'icons'  => [
                'time' => 'fas fa-clock',
            ],
        ]);

        $this->addVariables([
            'icon'          => $this->icon,
            'start_options' => $this->options,
            'end_options'   => array_merge($this->options, ['useCurrent' => false]),
        ]);

        $this->attribute(['autocomplete' => 'off']);

        return parent::render();
    }
}
